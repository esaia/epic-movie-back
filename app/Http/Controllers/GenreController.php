<?php

namespace App\Http\Controllers;

use App\Models\Genre;

class GenreController extends Controller
{
	public function index(): mixed
	{
		return Genre::all();
	}
}
