<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
	public function index(Request $request): Collection
	{
		$user = $request->user();
		$movies = $user->movie()->with('quote')->orderBy('created_at', 'desc')->get();
		return $movies;
	}

	public function store(CreateMovieRequest $request): Movie
	{
		$attributes = $request->validated();
		$imgPath = $request->file('img')->store('public/movies/');
		$title = ['en' => $attributes['title_en'], 'ka' => $attributes['title_ka']];
		$director = ['en' => $attributes['director_en'], 'ka' => $attributes['director_ka']];
		$description = ['en' => $attributes['description_en'], 'ka' => $attributes['description_ka']];
		$genre = json_decode($attributes['genre'], true);
		$attributes = [
			'title'       => $title,
			'genre'       => $genre,
			'date'        => $attributes['date'],
			'director'    => $director,
			'description' => $description,
			'img'         => $imgPath,
			'user_id'     => $attributes['user_id'],
		];
		$movie = Movie::Create($attributes);
		return $movie;
	}

	public function show($id): Movie
	{
		$movie = Movie::with(['quote' => function ($query) {
			$query->orderBy('created_at', 'desc');
		}])->findOrFail($id);

		$this->authorize('isValidUser', $movie);

		return $movie;
	}

	public function update(UpdateMovieRequest $request, $id): Movie
	{
		$attributes = $request->validated();

		$title = ['en' => $attributes['title_en'], 'ka' => $attributes['title_ka']];
		$director = ['en' => $attributes['director_en'], 'ka' => $attributes['director_ka']];
		$description = ['en' => $attributes['description_en'], 'ka' => $attributes['description_ka']];
		$genre = json_decode($attributes['genre'], true);
		$attributes = [
			'title'       => $title,
			'genre'       => $genre,
			'date'        => $attributes['date'],
			'director'    => $director,
			'description' => $description,
		];
		if ($request->hasFile('img')) {
			$imgPath = $request->file('img')->store('public/movies');
			$attributes['img'] = $imgPath;
		}
		$movie = Movie::findOrFail($id);
		$this->authorize('isValidUser', $movie);
		$movie->update($attributes);
		return  $movie;
	}

	public function destroy($id): JsonResponse
	{
		$movie = Movie::findOrFail($id);
		$this->authorize('isValidUser', $movie);
		$movie->delete();
		return Response()->json(['msg' => 'movie deleted'], 200);
	}
}
