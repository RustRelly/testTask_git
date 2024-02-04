<?php

namespace App\Http\Controllers;

use App\Models\Book;
use http\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Вывод всех книг
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $books = Book::with('authors:id,name')->select('id', 'name', 'available')->get();
        return response()->json($books);
    }

    /**
     * Сохранение книги с указанием авторов
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'authors' => 'required|array',
                'authors.*' => 'numeric|distinct|exists:authors,id',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            DB::transaction(
                function () use ($request) {
                    $book = Book::create(
                        [
                            'name' => $request->input('name'),
                        ]
                    );

                    $book->authors()->sync($request->input('authors'));
                }
            );

            return response()->json(
                [
                    'message' => 'Книга успешно добавлена',
                ]
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Выдача книги
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOut(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|min:1',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $book = Book::where('name', $request->name)->where('available', 1)->firstOrFail();
            $book->available = false;
            $book->save();
            return response()->json("Книга {$request->name} с идентификатором {$book->id} выдана");
        } catch (ModelNotFoundException $e) {
            return response()->json('Доступной книги с таким названием не существует');
        }
    }

    /**
     * Списание книги
     *
     * @param int $bookId - идентификатор книги
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $bookId)
    {
        $book = Book::find($bookId);

        if ($book) {
            try {
                DB::transaction(
                    function () use ($book) {
                        $book->authors()->detach();
                        $book->delete();
                    }
                );
                return response()->json("Книга $bookId успешно списана");
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json("Книга $bookId не найдена");
        }
    }
}
