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


class BookController extends BaseController
{
   /* public function index(Request $request): JsonResponse
    {
        $languageCode = $request->header('Language_Code', 'en');

        $columns = [
            'title_en' => 'title_en',
            'title_ar' => 'title_ar',
            'author_name_en' => 'author_name_en',
            'author_name_ar' => 'author_name_ar',
            'description_en' => 'description_en',
            'description_ar' => 'description_ar'
        ];
        $selectedColumns = [];

        foreach ($columns as $key => $column) {
            if (($languageCode == 'en' && strpos($key, 'en_')!== 0) || strpos($key, $languageCode. '_') === 0) {
                $selectedColumns[] = $column;
            } elseif ($languageCode == 'ar' && strpos($key, 'ar_') === 0) {
                $selectedColumns[] = $column;
            }
        }

        $books = Book::select($selectedColumns)->get();
        //$books = $query->get();

        $transformedBooks = [];
        foreach ($books as $book) {
            $transformedBook = [
                'id' => $book->id,
                'title' => $book->title_en?? $book->title_ar,
                'file' => $book->file,
                'cover' =>$book->cover,
                'author_name' => $book->author_name_en?? $book->author_name_ar,
                'points' => $book->points,
                'description' => $book->description_en?? $book->description_ar,
                'total_pages' => $book->total_pages,
                'type_id' => $book->type_id,
            ];
            $transformedBooks[] = $transformedBook;
        }

        // Prepare the response
        $response = [
            'books' => $transformedBooks,
            'message' => 'Books retrieved successfully.'
        ];

        // Send the response
        return $this->sendResponse($response, 'Books retrieved successfully.');


}*/
   public function index(Request $request): JsonResponse
    {
        $languageCode = $request->header('Language_Code', 'en');
        $selectedColumns = [
            'id', 'file', 'cover','title_en','title_ar', 'author_name_en', 'author_name_ar', 'points', 'description_en', 'description_ar', 'total_pages', 'type_id'
        ];

        $filteredColumns = array_filter($selectedColumns, function ($column) use ($languageCode) {
            return (strpos($column, $languageCode. '_') === 0) || (strpos($column, 'en_')!== 0 && $languageCode === 'en')
                || (strpos($column, 'ar_') === 0 && $languageCode === 'ar');
        });

        $books = Book::select($filteredColumns)->get();

        $transformedBooks = [];
        foreach ($books as $book) {
            $transformedBook = [
                'id' => $book->id,
                'title' => $book->title_en?? $book->title_ar,
                'file' => $book->file,
                'cover' => $book->cover,
                'author_name' => $book->author_name_en?? $book->author_name_ar,
                'points' => $book->points,
                'description' => $book->description_en?? $book->description_ar,
                'total_pages' => $book->total_pages,
                'type_id' => $book->type_id,
            ];
            $transformedBooks[] = $transformedBook;
        }

        $response = [
            'books' => $transformedBooks,
            'message' => 'Books retrieved successfully.'
        ];

        return $this->sendResponse($response, 'Books retrieved successfully.');
    }



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

        return $this->sendResponse($book, 'Book created successfully.');
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
    public function showDetails($id): JsonResponse
    {
        $book = Book::find($id);

        if (is_null($book)) {
            return $this->sendError('Book not found');
        }

        $rating = $this->avgRating($id);

        $details = Book::select('id', 'title', 'cover', 'total_pages', 'author_name', 'points', 'description', 'type_id',)
            ->where('id', $id)
            ->first();

        return $this->sendResponse([
            'Rating' => $rating,
            'Details' => $details,
        ], 'Book details retrieved successfully.');

    }
//show most reading books
public function mostReading (){

    $bookId = Book::all()->pluck('id');
    $mostReadBooks = Shelf::where('book_id',$bookId)->where('status','reading');
    Shelf::with('Book')->select('book_id', DB::raw('COUNT(book_id) as most_reading'))
    ->groupBy('book_id')->orderBy('most_reading', 'desc')->take(10)->get();

    return $this->sendResponse($mostReadBooks, 'Most reading books retrieved successfully');
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

//enable book and createShelf
    public function show($id, Request $request)
    {
        $book = Book::findOrFail($id);
        $userId = Auth::user()->id;
        $shelf = Shelf::where('user_id', $userId)->where('book_id', $id)->first();

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
                    'progress' => 1,
                ]);

                $file = $book->file;

                return $this->sendResponse([
                    'file' => $file,
                ], 'Book opened successfully.');
            }

        } elseif ($shelf->status == 'watch_later') {

            if ($request->user()->my_points < $book->points) {
                return $this->sendError('Oops! Your pophpints aren\'t enough to open this book.');
            } else {

                $request->user()->update(['my_points' => $request->user()->my_points - $book->points]);
                $shelf->update([
                    'status' => 'reading',
                    'progress' => 1,
                ]);

                $file = $book->file;

                return $this->sendResponse([
                    'book_data' => $file,
                ], 'Book opened successfully.');
            }

        } elseif ($shelf->status == 'reading' || $shelf->status == 'finished') {

            $file = $book->file;

            return $this->sendResponse([
                'file' => $file,
            ], 'Book opened successfully.');
        }}

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
}
