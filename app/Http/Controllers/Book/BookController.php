<?php

namespace App\Http\Controllers\Book;


use App\Http\Requests\BookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Shelf;
use App\Models\Type;
use Cassandra\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends BaseController
{

//show all books
    public function index(): JsonResponse
    {
        $books = Book::all();
        return $this->sendResponse($books, 'Books retrieved successfully.');
    }

//store book
    public function store(BookRequest $request): JsonResponse
    {

        /*  $pdfName = $request->file->getClientOriginalName();
          $pdfpath = $request->file->storeAs('books/files', $pdfName,'public');
          $pdfUrl = Storage::url($pdfpath);*/


        $image = time() . '-' . $request->title . '.' . $request->file('cover')->extension();
        $request->cover->move(public_path('books/cover_images'), $image);
        $image = 'books/cover_images/' . $image;

        $file = time() . '-' . $request->title . '.' . $request->file('file')->extension();
        $request->file->move(public_path('books/files'), $file);
        $file = 'books/files/' . $file;

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

//show book details without 'file'
    public function showDetails($id): JsonResponse
    {
        $book = Book::find($id);

        if (is_null($book)) {
            return $this->sendError('Book not found');
        }
        $details = Book::select('id', 'title', 'cover', 'total_pages', 'author_name', 'points', 'description', 'type_id',)
            ->where('id', $id)
            ->first();

        return $this->sendResponse($details, 'Book retrieved successfully');
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
 /**   public function findByName(Request $request): \Illuminate\Http\JsonResponse
    {
        $bookName = $request->input('name');
        $books = Book::where('title', 'like', '%' . $bookName . '%')->get();

        if ($books->isEmpty()) {
            return $this->sendError('Book not found');
        }

        return $this->sendResponse($books, 'Books retrieved successfully');
    }*/

//search books by author name
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

                $bookData = $this->getFile($id);

                return $this->sendResponse([
                    'Shelf' => $newShelf,
                    'book_data' => $bookData,
                ], 'Shelf Created and Book opened successfully.');
            }

        } elseif ($shelf->status == 'watch_later') {

            if ($request->user()->my_points < $book->points) {
                return $this->sendError('Oops! Your points aren\'t enough to open this book.');
            } else {

                $request->user()->update(['my_points' => $request->user()->my_points - $book->points]);
                $shelf->update([
                    'status' => 'reading',
                    'progress' => 1,
                ]);

                $bookData = $this->getFile($id);

                return $this->sendResponse([
                    'Shelf' => $shelf,
                    'book_data' => $bookData,
                ], 'Shelf Updated and Book opened successfully.');
            }

        } elseif ($shelf->status == 'reading' || $shelf->status == 'finished') {

            $bookData = $this->getFile($id);

            return $this->sendResponse([
                'shelf' => $shelf,
                'book_data' => $bookData,
            ], 'Shelf Updated and Book opened successfully.');

            // Filter out unwanted fields
            /*  $filteredBookData = [
                  'success' => isset($bookData['success'])? $bookData['success'] : false,
                  'data' => isset($bookData['data'])? $bookData['data'] : '',
                  'message' => isset($bookData['message'])? $bookData['message'] : ''
              ];

              return $this->sendResponse([
                  'shelf' => $shelf,
                  'book_data' => $filteredBookData,
              ], 'Shelf Updated and Book opened successfully.');
          }*/

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
