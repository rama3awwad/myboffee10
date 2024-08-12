<?php

namespace App\Http\Controllers\Book;


use App\Models\Book;
use App\Models\Type;
use App\Models\Shelf;
use App\Models\Rating;
use Cassandra\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateBookRequest;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Http\Controllers\BaseController as BaseController;

class BookController extends BaseController
{

    const BATCH_SIZE = 10;


    public function index()
    {
        $books = Book::all();
        $user = auth()->user();
        $userLocale = $user ? $user->lang : 'en';
        $translatedBooks = [];

        if ($userLocale == 'ar' || $userLocale == 'en') {
            $translator = new GoogleTranslate();
            $translator->setSource($userLocale == 'ar' ? 'en' : 'ar');
            $translator->setTarget($userLocale);

            $batches = $this->divideIntoBatches($books, self::BATCH_SIZE);

            foreach ($books as $book) {
                // Check if translation is already available in database or cache
                $translatedTitle = Cache::remember("book_{$book->id}_title_{$userLocale}", 3600, function () use ($translator, $book) {
                    return $translator->translate($book->title);
                });

                $translatedAuthorName = Cache::remember("book_{$book->id}_author_name_{$userLocale}", 3600, function () use ($translator, $book) {
                    return $translator->translate($book->author_name);
                });

                $translatedDescription = Cache::remember("book_{$book->id}_description_{$userLocale}", 3600, function () use ($translator, $book) {
                    return $translator->translate($book->description);
                });

                $translatedBooks[] = [
                    'id' => $book->id,
                    'title' => $translatedTitle,
                    'author_name' => $translatedAuthorName,
                    'description' => $translatedDescription,
                    'cover' => $book->cover,
                    'total_pages' => $book->total_pages,
                    'points' => $book->points,
                    'type_id' => $book->type_id
                ];
            }
        } else {
            // If the user's language is not 'ar' or 'en', return the books without translation
            $translatedBooks = $books;
        }


        return $this->sendResponse($translatedBooks, "Books retrieved successfully.");
    }
    private static function divideIntoBatches($items, $batchSize)
    {
        $itemsArray = $items->toArray(); // Convert the collection to an array
        $batches = [];
        for ($i = 0; $i < count($itemsArray); $i += $batchSize) {
            $batches[] = array_slice($itemsArray, $i, $batchSize);
        }
        return $batches;
    }

    //enable book and createShelf
    public function show($id, Request $request)
    {
        $book = Book::findOrFail($id);
        $userId = Auth::user()->id;
        $shelf = Shelf::where('user_id', $userId)->where('book_id', $id)->first();
        $userLocale = auth()->user()->lang;

        if (!$shelf) {

            if ($request->user()->my_points < $book->points) {
                return $this->sendError('Oops! Your points aren\'t enough to open this book.');
            } else {

                $request->user()->update([
                    'my_points' => $request->user()->my_points - $book->points
                ]);

                $newShelf = Shelf::create([
                    'user_id' => $userId,
                    'book_id' => $id,
                    'status' => 'reading',
                    'progress' => 0,
                ]);

                $book = DB::table('books')
                    ->join('types', 'books.type_id', '=', 'types.id')
                    ->where('books.id', $id)
                    ->select(
                        'books.id',
                        'books.title as title',
                        'books.cover',
                        'books.file',
                        'books.author_name',
                        'books.description',
                        'books.total_pages',
                        'books.points',
                        'types.name as type_name'
                    )
                    ->first();

                if ($userLocale == 'ar') {
                    $translator = new GoogleTranslate();
                    $translator->setSource('en');
                    $translator->setTarget('ar');

                    $book->title = $translator->translate($book->title);
                    $book->author_name = $translator->translate($book->author_name);
                    $book->description = $translator->translate($book->description);
                    $book->type_name = $translator->translate($book->type_name);
                }

                if ($userLocale == 'en') {
                    $translator = new GoogleTranslate();
                    $translator->setSource('ar');
                    $translator->setTarget('en');

                    $book->title = $translator->translate($book->title);
                    $book->author_name = $translator->translate($book->author_name);
                    $book->description = $translator->translate($book->description);
                    $book->type_name = $translator->translate($book->type_name);
                }

                $file = $book->file;

                return $this->sendResponse([
                    'Shelf_id' => $newShelf->id,
                    'progress' => (int) $newShelf->progress,
                    'file' => $book,
                ], 'Book opened successfully.');
            }
        } elseif ($shelf->status == 'watch_later') {

            if ($request->user()->my_points < $book->points) {
                return $this->sendError('Oops! Your points aren\'t enough to open this book.');
            } else {

                $request->user()->update(['my_points' => $request->user()->my_points - $book->points]);
                $shelf->update([
                    'status' => 'reading',
                    'progress' => 0,
                ]);
                $book = DB::table('books')
                    ->join('types', 'books.type_id', '=', 'types.id')
                    ->where('books.id', $id)
                    ->select(
                        'books.id',
                        'books.title',
                        'books.cover',
                        'books.file',
                        'books.author_name',
                        'books.description',
                        'books.total_pages',
                        'books.points',
                        'types.name as type_name'
                    )
                    ->first();

                if ($userLocale == 'ar') {
                    $translator = new GoogleTranslate();
                    $translator->setSource('en');
                    $translator->setTarget('ar');

                    $book->title = $translator->translate($book->title);
                    $book->author_name = $translator->translate($book->author_name);
                    $book->description = $translator->translate($book->description);
                    $book->type_name = $translator->translate($book->type_name);
                }

                if ($userLocale == 'en') {
                    $translator = new GoogleTranslate();
                    $translator->setSource('ar');
                    $translator->setTarget('en');

                    $book->title = $translator->translate($book->title);
                    $book->author_name = $translator->translate($book->author_name);
                    $book->description = $translator->translate($book->description);
                    $book->type_name = $translator->translate($book->type_name);
                }

                $file = $book->file;

                return $this->sendResponse([
                    'Shelf_id' => $shelf->id,
                    'progress' => (int) $shelf->progress,
                    'file' => $book
                ], 'Book opened successfully.');
            }
        } elseif ($shelf->status == 'reading' || $shelf->status == 'finished') {

            $book = DB::table('books')
                ->join('types', 'books.type_id', '=', 'types.id')
                ->where('books.id', $id)
                ->select(
                    'books.id',
                    'books.title',
                    'books.cover',
                    'books.file',
                    'books.author_name',
                    'books.description',
                    'books.total_pages',
                    'books.points',
                    'types.name as type_name'
                )
                ->first();

            if ($userLocale == 'ar') {
                $translator = new GoogleTranslate();
                $translator->setSource('en');
                $translator->setTarget('ar');

                $book->title = $translator->translate($book->title);
                $book->author_name = $translator->translate($book->author_name);
                $book->description = $translator->translate($book->description);
                $book->type_name = $translator->translate($book->type_name);
            }

            if ($userLocale == 'en') {
                $translator = new GoogleTranslate();
                $translator->setSource('ar');
                $translator->setTarget('en');

                $book->title = $translator->translate($book->title);
                $book->author_name = $translator->translate($book->author_name);
                $book->description = $translator->translate($book->description);
                $book->type_name = $translator->translate($book->type_name);
            }
            $file = $book->file;

            return $this->sendResponse([
                'Shelf_id' => $shelf->id,
                'progress' => (int) $shelf->progress,
                'file' => $book,
            ], 'Book opened successfully.');
        }
    }

    //store book
    public function store(BookRequest $request): JsonResponse
    {

        /*  $pdfName = $request->file->getClientOriginalName();
          $pdfpath = $request->file->storeAs('books/files', $pdfName,'public');
          $pdfUrl = Storage::url($pdfpath);*/


        $image = time() . '-' . $request->title . '.' . $request->file('cover')->extension();
        $request->cover->move(public_path('/books/cover_images'), $image);
        $image = '/books/cover_images/' . $image;

        $file = time() . '-' . $request->title . '.' . $request->file('file')->extension();
        $request->file->move(public_path('/books/files'), $file);
        $file = '/books/files/' . $file;

        $data = $request->validated();

        $book = Book::create([
            'title' => $request->title,
            'file' => $file,
            'cover' => $image,
            'author_name' => $request->author_name,
            'points' => $request->points,
            'description' => $request->description,
            'total_pages' => $request->total_pages,
            'type_id' => $request->type_id,
        ]);

        return $this->sendResponse($data, 'Book created successfully.');
    }

    //show avg rating of book
    public function avgRating($id)
    {

        $sumOfRatings = Rating::where('book_id', $id)->sum('rate');
        $countOfRatings = Rating::where('book_id', $id)->count();

        if ($countOfRatings > 0) {
            $averageRating = $sumOfRatings / $countOfRatings;

            return $averageRating;
        }

        return null;
    }

    //show book details without 'file'
    public function showDetails($id, Request $request): JsonResponse
    {
        $book = Book::find($id);
        $userLocale = $request->user()->lang;

        if (is_null($book)) {
            return $this->sendError('Book not found');
        }
        $rating = $this->avgRating($id);

        $details = Book::select('id', 'title', 'cover', 'total_pages', 'author_name', 'points', 'description', 'type_id',)
            ->where('id', $id)
            ->first();

        if ($userLocale == 'ar') {
            $translator = new GoogleTranslate();
            $translator->setSource('en');
            $translator->setTarget('ar');

            $details->title = $translator->translate($details->title);
            $details->author_name = $translator->translate($details->author_name);
            $details->description = $translator->translate($details->description);
        }

        if ($userLocale == 'en') {
            $translator = new GoogleTranslate();
            $translator->setSource('ar');
            $translator->setTarget('en');

            $details->title = $translator->translate($details->title);
            $details->author_name = $translator->translate($details->author_name);
            $details->description = $translator->translate($details->description);
        }
        return $this->sendResponse([
            'Rating' => $rating,
            'Details' => $details,
        ], 'Book details retrieved successfully.');
    }

    //show most reading books
    public function mostReading()
    {

        $mostReadBooks = DB::table('shelves')
            ->leftJoin('books', 'books.id', '=', 'shelves.book_id')
            ->select('books.*', 'shelves.status', DB::raw("COUNT(shelves.book_id) AS most_reading"))
            ->where('shelves.status', 'reading')
            ->groupBy(
                'books.id',
                'file',
                'cover',
                'title',
                'author_name',
                'points',
                'description',
                'total_pages',
                'type_id',
                'created_at',
                'updated_at',
                'shelves.status'
            )
            ->orderBy('most_reading', 'desc')
            ->take(10)
            ->get();

        return $this->sendResponse($mostReadBooks, 'Most reading books retrieved successfully');
    }
    public function mostRating()
    {

        $mostRateBooks = DB::table('ratings')
            ->leftJoin('books', 'books.id', '=', 'ratings.book_id')
            ->select('books.*', 'ratings.rate', DB::raw("AVG(ratings.rate) AS most_rating"))
            ->groupBy(
                'books.id',
                'file',
                'cover',
                'title',
                'author_name',
                'points',
                'description',
                'total_pages',
                'type_id',
                'created_at',
                'updated_at',
                'ratings.rate'
            )
            ->orderBy('most_rating', 'desc')
            ->take(15)
            ->get();

        return $this->sendResponse($mostRateBooks, 'Most rating books retrieved successfully');
    }

    //show file only
    public function getFile($bookId)
    {
        $book = Book::find($bookId);

        if ($book === null) {
            return $this->sendError('Book not find');
        }
        $bookData = $book->file;
        return $this->sendResponse($bookData, 'File retrieved successfully');
    }

    //show book by its id for admin
    public function Ashow($id): JsonResponse
    {
        $book = Book::find($id);

        if (is_null($book)) {

            return $this->sendError('Book not found');
        }

        return $this->sendResponse($book, 'Book retrieved successfully');
    }


    //search book by its name
    /*    public function findByName(Request $request): \Illuminate\Http\JsonResponse
    {
        $bookName = $request->input('name');
        $books = Book::where('title', 'like', '%' . $bookName . '%')->get();

        if ($books->isEmpty()) {
            return $this->sendError('Book not found');
        }

        return $this->sendResponse($books, 'Books retrieved successfully');
    }*/

    //search books book name or author name

    public function author(Request $request): \Illuminate\Http\JsonResponse
    {

        $find = $request->input('name');
        $user = auth()->user();
    $userLocale = $user ? $user->lang : 'en';

        if ($userLocale == 'ar') {
            $translator = new GoogleTranslate();
            $translatedFind = $translator->setSource('ar')->setTarget('en')->translate($find);

            $books = Book::where(function ($query) use ($find,$translatedFind) {
                $query->where('title', 'like', '%' . $find . '%')
                    ->orWhere('author_name', 'like', '%' . $find . '%')
                    ->orWhere('title', 'like', '%' . $translatedFind . '%')
                    ->orWhere('author_name', 'like', '%' . $translatedFind . '%');
            })->get();
            $translator1 = new GoogleTranslate();
            $translator1->setSource('en');
            $translator1->setTarget('ar');
            foreach ($books as $book) {
                $book->title = $translator1->translate($book->title);
                $book->author_name = $translator1->translate($book->author_name);
                $book->description = $translator1->translate($book->description);
            }
        }

        elseif ($userLocale == 'en') {
            $translator = new GoogleTranslate();
            $translatedFind = $translator->setSource('en')->setTarget('ar')->translate($find);

            $books = Book::where(function ($query) use ($find,$translatedFind) {
                $query->where('title', 'like', '%' . $find . '%')
                    ->orWhere('author_name', 'like', '%' . $find . '%')
                    ->orWhere('title', 'like', '%' . $translatedFind . '%')
                    ->orWhere('author_name', 'like', '%' . $translatedFind . '%');
            })->get();

            $translator1 = new GoogleTranslate();
            $translator1->setSource('ar');
            $translator1->setTarget('en');
            foreach ($books as $book) {
                $book->title = $translator1->translate($book->title);
                $book->author_name = $translator1->translate($book->author_name);
                $book->description = $translator1->translate($book->description);
            }
        }

        if ($books->isEmpty()) {
            return $this->sendError('Book or author not found');
        }

        return $this->sendResponse($books, 'Books retrieved successfully');
    }

    //delete book
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book not found');
        }

        Storage::delete($book->cover);
        Storage::delete($book->file);

        $book->delete();
        return $this->sendResponse(null, 'Book deleted successfully');
    }


    // Show all books of a specific type
    public function showBooksByType($typeId): JsonResponse
    {
        $books = Book::where('type_id', $typeId)->get();
        $userLocal = auth()->user()->lang;

        if($userLocal == 'ar'){
            $translator = new GoogleTranslate();
            $translator->setSource('en');
            $translator->setTarget('ar');

            forEach($books as $book){
            $book->title = $translator->translate($book->title);
            $book->author_name = $translator->translate($book->author_name);
            $book->description = $translator->translate($book->description);
        }
    }

        if($userLocal == 'en'){
            $translator = new GoogleTranslate();
            $translator->setSource('ar');
            $translator->setTarget('en');

            foreach($books as $book){
            $book->title = $translator->translate($book->title);
            $book->author_name = $translator->translate($book->author_name);
            $book->description = $translator->translate($book->description);
        }
    }

        if ($books->isEmpty()) {
            return $this->sendError('No books found for this type.');
        }

        return $this->sendResponse($books, 'Books retrieved successfully.');
    }

    //update book
    public function update(BookRequest $request, $id): JsonResponse
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book not found');
        }
        if ($request->image != null) {
            if (File::exists($book->cover)) {
                File::delete(public_path($book->cover));
            }
            $image = time() . '-' . $book->title . '.' . $request->file('cover')->extension();
            $request->cover->move(public_path('books/cover_images'), $image);
            $book->update([
                'cover' => 'books/cover_images/' . $image,
            ]);
        }
        if ($request->file != null) {

            if (File::exists($book->file)) {
                File::delete(public_path($book->file));
            }
            $file = time() . '-' . $book->title . '.' . $request->file('file')->extension();
            $request->file->move(public_path('books/files'), $file);
            $book->update([
                'file' => 'books/files/' . $file,
            ]);

            $book->update($request->all());
            return $this->sendResponse($book, 'Book updated successfully.');
        }
    }

    public function updateImage(Request $request, $id)
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book not found');
        }
        $request->validate(['cover' => 'file|image', 'file' => 'file']);
        if (File::exists($book->cover)) {
            File::delete(public_path($book->cover));
        }
        $image = time() . '-' . $book->title . '.' . $request->file('cover')->extension();
        $request->cover->move(public_path('books/cover_images'), $image);
        $book->update([
            'cover' => 'books/cover_images/' . $image,
        ]);
        if (File::exists($book->file)) {
            File::delete(public_path($book->file));
        }
        $file = time() . '-' . $book->title . '.' . $request->file('file')->extension();
        $request->file->move(public_path('books/files'), $file);
        $book->update([
            'file' => 'books/files/' . $file,
        ]);
        return $this->sendResponse($book, 'Book updated successfully.');
    }

    //calculate ratio of readings for each type in default - last week - last month - last year
    public function typeReading($periodVariable)
    {
        $types = DB::table('types')->get();

        $result = [];

        $countAllBooks = DB::table('books')->count();

        switch ($periodVariable) {
            case 0:
                $period = null;
                $periodLabel = 'default';
                break;

            case 1:
                $period = now()->subWeek();
                $periodLabel = 'last week';
                break;

            case 2:
                $period = now()->subMonth();
                $periodLabel = 'last month';
                break;

            case 3:
                $period = now()->subYear();
                $periodLabel = 'last year';
                break;

            default:
                return $this->sendError('Invalid period specified.', 400);
        }

        foreach ($types as $type) {
            $typeBooks = DB::table('books')
                ->where('type_id', $type->id)
                ->count();

            $readingOrFinished = DB::table('books')
                ->join('shelves', 'books.id', '=', 'shelves.book_id')
                ->where('books.type_id', $type->id)
                ->whereIn('shelves.status', ['reading', 'finished']);

            $countAllReadingOrFinished = DB::table('books')
                ->join('shelves', 'books.id', '=', 'shelves.book_id')
                ->whereIn('shelves.status', ['reading', 'finished'])
                ->count();


            if ($period) {
                $readingOrFinished->where('shelves.updated_at', '>=', $period);
            }

            $readingOrFinishedCount = $readingOrFinished->count();

            if ($typeBooks > 0) {
                $typeBookRatio = $typeBooks / $countAllBooks;
                $readingOrFinishedRatio = $readingOrFinishedCount / $typeBooks;
            } else {
                $typeBookRatio = 0;
                $readingOrFinishedRatio = 0;
            }

            $floatTypeBookRatio = round($typeBookRatio, 2);
            $floatReadingOrFinishedRatio = round($readingOrFinishedRatio, 2);

            $result[] = [
                'type_id' => $type->id,
                'type_name' => $type->name,
                'count_type_books' => $typeBooks,
                'type_book_ratio' => (float) $floatTypeBookRatio,
                'count_type_reading_or_finished' => $readingOrFinishedCount,
                'reading_or_finished_ratio' => (float) $floatReadingOrFinishedRatio
            ];
        }

        return $this->sendResponse([
            'period' =>$periodLabel,
            'count all books' => $countAllBooks,
            'count all reading' => $countAllReadingOrFinished,
            'result' => $result,
            ], 'Counts retrieved successfully.');
    }
}
