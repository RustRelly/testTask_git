<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all(['id', 'name']);
        return response()->json($authors);
    }
}
