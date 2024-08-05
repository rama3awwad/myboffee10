<?php

namespace App\Http\Controllers\Book;


use App\Http\Requests\BookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Rating;
use App\Models\Shelf;
use App\Models\Type;
use Cassandra\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BookController extends BaseController
{

 /*  public function index(Request $request)
    {
        $languageCode = $request->header('Language_Code', 'en');
        $selectedColumns = [
            'id', 'file', 'cover','title_en','title_ar', 'author_name_en', 'author_name_ar',
            'points', 'description_en', 'description_ar', 'total_pages', 'type_id'
        ];

        $filteredColumns = array_filter($selectedColumns, function ($column) use ($languageCode) {
     return  (strpos($column, $languageCode. '_') === 0) ||
             (strpos($column, 'en_')!== 0 && $languageCode === 'en')||
             (strpos($column, 'ar_') === 0 && $languageCode === 'ar');
        });

        $books = Book::get();

        $transformedBooks = [];
      if($languageCode == 'en'){
        foreach ($books as $book) {

            $transformedBook = [
                'id' => $book->id,
                'title' => $book->title_en,
                'file' => $book->file,
                'cover' => $book->cover,
                'author_name' => $book->author_name_en,
                'points' => $book->points,
                'description' => $book->description_en,
                'total_pages' => $book->total_pages,
                'type_id' => $book->type_id,

            ];
            $transformedBooks[] = $transformedBook;
        }}
        elseif ($languageCode == 'ar') {
             foreach ($books as $book) {
            $transformedBook = [
                'id' => $book->id,
                'title' => $book->title_ar,
                'file' => $book->file,
                'cover' => $book->cover,
                'author_name' => $book->author_name_ar,
                'points' => $book->points,
                'description' => $book->description_ar,
                'total_pages' => $book->total_pages,
                'type_id' => $book->type_id,

            ];
            $transformedBooks[] = $transformedBook;
        }
        }


        $response = [
            'books' => $transformedBooks,
                   ];

        return $this->sendResponse($response, 'Books retrieved successfully.');
    }*/



//show all books
   /*  public function index(Request $request): JsonResponse
    {
        $books = Book::all();
        return $this->sendResponse($books, 'Books retrieved successfully.');
    }
*/



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

        if ($countOfRatings >= 0) {
            $averageRating = $sumOfRatings / $countOfRatings;

            return $averageRating;
        }

        return null;

    }

//show book details without 'file'
    public function showDetails($id ,Request $request): JsonResponse
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
                $translator->setSource('en'); // اللغة المصدر
                $translator->setTarget('ar'); // اللغة الهدف

                $book->title = $translator->translate($book->title);
                $book->author_name = $translator->translate($book->author_name);
                $book->description = $translator->translate($book->description);
            }
        return $this->sendResponse([
            'Rating' => $rating,
            'Details' => $details,
        ], 'Book details retrieved successfully.');

    }

//show most reading books
    public function mostReading (){

     $mostReadBooks = DB::table('shelves')
         ->leftJoin('books', 'books.id', '=', 'shelves.book_id')
         ->select('books.*', 'shelves.status', DB::raw("COUNT(shelves.book_id) AS most_reading"))
         ->where('shelves.status', 'reading')
         ->groupBy('books.id', 'file', 'cover','title','author_name',
                   'points','description', 'total_pages', 'type_id',
                   'created_at','updated_at','shelves.status')
         ->orderBy('most_reading', 'desc')
         ->take(10)
         ->get();

         return $this->sendResponse($mostReadBooks, 'Most reading books retrieved successfully');
}
    public function mostRating (){

        $mostRateBooks = DB::table('ratings')
            ->leftJoin('books', 'books.id', '=', 'ratings.book_id')
            ->select('books.*', 'ratings.rate', DB::raw("AVG(ratings.rate) AS most_rating"))
            ->groupBy('books.id', 'file', 'cover','title','author_name',
                    'points','description', 'total_pages', 'type_id',
                    'created_at','updated_at','ratings.rate')
            ->orderBy('most_rating','desc')
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

        // Search in both book title and author name
        $books = Book::where(function ($query) use ($find) {
            $query->where('title', 'like', '%'. $find. '%')
                ->orWhere('author_name', 'like', '%'. $find. '%');
        })->get();

        if ($books->isEmpty()) {
            return $this->sendError('Book not found');
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

        if ($books->isEmpty()) {
            return $this->sendError('No books found for this type.');
        }

        return $this->sendResponse($books, 'Books retrieved successfully.');
    }


    public function index($id)
    {
        $book = Book::findOrFail($id);
        $userLocale = auth()->user()->lang;

        if ($userLocale == 'ar') {
            $translator = new GoogleTranslate();
            $translator->setSource('en'); // اللغة المصدر
            $translator->setTarget('ar'); // اللغة الهدف

            $book->title = $translator->translate($book->title);
            $book->author_name = $translator->translate($book->author_name);
            $book->description = $translator->translate($book->description);
        }

        return $this->sendResponse($book,"");
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
                'my_points' => $request->user()->my_points - $book->points]);

            $newShelf = Shelf::create([
                'user_id' => $userId,
                'book_id' => $id,
                'status' => 'reading',
                'progress' => 0,
            ]);

            $book = DB::table('books')
                ->join('types', 'books.type_id', '=', 'types.id')
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
                    $translator->setSource('en'); // اللغة المصدر
                    $translator->setTarget('ar'); // اللغة الهدف
        
                    $book->title = $translator->translate($book->title);
                    $book->author_name = $translator->translate($book->author_name);
                    $book->description = $translator->translate($book->description);
                    $book->type_name = $translator->translate($book->type_name);
                }

                if ($userLocale == 'en') {
                    $translator = new GoogleTranslate();
                    $translator->setSource('ar'); // اللغة المصدر
                    $translator->setTarget('en'); // اللغة الهدف
        
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
                    $translator->setSource('en'); // اللغة المصدر
                    $translator->setTarget('ar'); // اللغة الهدف
        
                    $book->title = $translator->translate($book->title);
                    $book->author_name = $translator->translate($book->author_name);
                    $book->description = $translator->translate($book->description);
                    $book->type_name = $translator->translate($book->type_name);
                }

                if ($userLocale == 'en') {
                    $translator = new GoogleTranslate();
                    $translator->setSource('ar'); // اللغة المصدر
                    $translator->setTarget('en'); // اللغة الهدف
        
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
                $translator->setSource('en'); // اللغة المصدر
                $translator->setTarget('ar'); // اللغة الهدف
    
                $book->title = $translator->translate($book->title);
                $book->author_name = $translator->translate($book->author_name);
                $book->description = $translator->translate($book->description);
                $book->type_name = $translator->translate($book->type_name);
            }

            if ($userLocale == 'en') {
                $translator = new GoogleTranslate();
                $translator->setSource('ar'); // اللغة المصدر
                $translator->setTarget('en'); // اللغة الهدف
    
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

            $lang = new GoogleTranslate();

            if ($request->has('title')) {
                $data['title'] = $lang->setTarget('en')->setSource('ar')->translate($book['title']);
            }

            if ($request->has('author_name')) {
                $data['author_name'] = $lang->setTarget('en')->setSource('ar')->translate($data['author_name']);
            }

            if ($request->has('description')) {
                $data['description'] = $lang->setTarget('en')->setSource('ar')->translate($data['description']);
            }
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
        $request->validate(['cover'=>'file|image','file'=>'file']);
        if (File::exists($book->cover)){
            File::delete(public_path($book->cover));
        }
        $image = time() . '-' . $book->title . '.' . $request->file('cover')->extension();
        $request->cover->move(public_path('books/cover_images'),$image);
        $book->update([
            'cover'=>'books/cover_images/'.$image,
        ]);
        if (File::exists($book->file)){
            File::delete(public_path($book->file));
        }
        $file = time() . '-' . $book->title . '.' . $request->file('file')->extension();
        $request->file->move(public_path('books/files'),$file);
        $book->update([
            'file'=>'books/files/'.$file,
        ]);
        return $this->sendResponse($book, 'Book updated successfully.');

    }

    public function typeReading()
    {
        $types = DB::table('types')->get();

        $result = [];

        foreach ($types as $type) {
            $totalBooks = DB::table('books')
                ->where('type_id', $type->id)
                ->count();

            $readingOrFinishedCount = DB::table('books')
                ->join('shelves', 'books.id', '=', 'shelves.book_id')
                ->where('books.type_id', $type->id)
                ->whereIn('shelves.status', ['reading', 'finished'])
                ->where('shelves.updated_at', '>=', now()->subMonth())
                ->count();

            if ($totalBooks > 0) {
                $ratio = $readingOrFinishedCount / $totalBooks;
            } else {
                $ratio = 0;
            }

            $formattedRatio = (float) number_format($ratio, 2);

            $result[] = [
                'type_id' => $type->id,
                'type_name' => $type->name,
                'total_books' => $totalBooks,
                'reading_or_finished_count' => $readingOrFinishedCount,
                'ratio' => $formattedRatio
            ];
        }

        return $this->sendResponse($result, 'Counts retrieved successfully.');
    }


}
