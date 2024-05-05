<?php

namespace App\Http\Controllers\Book;


use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Shelve;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $image = time() . '-' . $request->title . '.' . $request->file('cover')->extension();
        $request->cover->move(public_path('books/cover_images'),$image);
        $image='books/cover_images/'.$image;

        $file = time() . '-' . $request->title . '.' . $request->file('file')->extension();
        $request->file->move(public_path('books/files'),$file);
        $file='books/files/'.$file;



        // Create a new book record with the uploaded file paths
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

    //show book by user (enable it then open)
    public function show($id)
    {
        $user = Auth::user();
        $book = Book::find($id);

        if (is_null($book)) {
            return $this->sendError('Book not found.');
        }

        // Check if the user has the book in their shelf
        $shelve = Shelve::where('book_id', $book->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$shelve) {
            // If the book is not in the user's shelf, check if the user has enough points to obtain the book
            if ($book->points > 0 && $user->my_points > 0) {
                if ($user->my_points < $book->points) {
                    $need = $book->points - $user->my_points;
                    return $this->sendError("You are not allowed to obtain this book because you don't have enough points, you need additional $need points.");
                } else {
                    // If the user has enough points, decrement the user's points and update the shelve status to 'reading'
                    $user->decrement('my_points', $book->points);
                    $shelve = new Shelve([
                        'book_id' => $book->id,
                        'user_id' => $user->id,
                        'status' => 'reading',
                        'progress' => 0,
                    ]);
                    $shelve->save();
                    return $this->sendResponse($book, 'Book retrieved successfully.');
                }
            } else {
                return $this->sendError('You do not have permission to view this book.');
            }
        } elseif ($shelve->status == 'reading' || $shelve->status == 'finished') {
            // If the book is already in the user's shelf with 'reading' or 'finished' status, show the book directly
            return $this->sendResponse($book, 'Book retrieved successfully.');
        } else {
            // If the book is in the user's shelf but not in 'reading' or 'finished' status, update the status to 'reading'
            $shelve->status = 'reading';
            $shelve->save(); // Save the updated shelve status
            return $this->sendResponse($book, 'Book retrieved successfully.');
        }
    }


    //show book by its id for admin
    public function Ashow($id): JsonResponse
    {
        $book = Book::find($id);

        if (is_null($book)) {
            return $this->sendError('Book not found');
        }

        // Assuming you have a method to send a response, similar to your sendResponse method
        return $this->sendResponse($book, 'Book retrieved successfully');
    }

    //search book by name
    public function findByName(Request $request): \Illuminate\Http\JsonResponse
    {
        $bookName = $request->input('name');
        $books = Book::where('title', 'like', '%' . $bookName . '%')->get();

        if ($books->isEmpty()) {
            return $this->sendError('Book not found');
        }

        return $this->sendResponse($books, 'Books retrieved successfully');
    }

    //update book
    public function update(BookRequest $request, $id): JsonResponse
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book not found');
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = time() . '-' . $request->title . '.' . $request->file('file')->extension();
            $request->file->move(public_path('books/files'), $file);
            $file = 'books/files/' . $file;
            $book->file = $file;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = time() . '-' . $request->image . '.' . $request->file('image')->extension();
            $request->image->move(public_path('books/images'), $image);
            $image = 'books/images/' . $image;
            $book->image = $image;
        }

        // Update other fields
        $book->title = $request->title;
        $book->author_name = $request->author_name;
        $book->points = $request->points;
        $book->description = $request->description;
        $book->total_pages = $request->total_pages;
        $book->type_id = $request->type_id;

        $book->save();

        return $this->sendResponse($book, 'Book updated successfully.');
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

}
