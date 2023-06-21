<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
	public function index(Request $request)
	{
		$perPage = 6;
		$page = $request->query('page', 1);
		$offset = ($page - 1) * $perPage;

		$quotes = Quote::orderBy('updated_at', 'desc')
						->offset($offset)
						->limit($perPage)
						->get();

		$totalPages = ceil(Quote::all()->count() / $perPage);

		$searchQuery = $request->input('searchQuery');

		if ($searchQuery) {
			if (str_starts_with($searchQuery, '@')) {
				$quotes = Quote::whereHas('movie', function ($query) use ($searchQuery) {
					$query->where('title->en', 'like', '%' . substr($searchQuery, 1) . '%')->orWhere('title->ka', 'like', '%' . substr($searchQuery, 1) . '%');
				})->get();
				$totalPages = ceil($quotes->count() / $perPage);
				$quotes = Quote::whereHas('movie', function ($query) use ($searchQuery) {
					$query->where('title->en', 'like', '%' . substr($searchQuery, 1) . '%')->orWhere('title->ka', 'like', '%' . substr($searchQuery, 1) . '%');
				})
					->offset($offset)
					->limit($perPage)
					->get();
			} elseif (str_starts_with($searchQuery, '#')) {
				$quotes = Quote::where('quote->en', 'like', '%' . substr($searchQuery, 1) . '%')
				->orWhere('quote->ka', 'like', '%' . substr($searchQuery, 1) . '%')
				->get();
				$totalPages = ceil($quotes->count() / $perPage);
				$quotes = Quote::where('quote->en', 'like', '%' . substr($searchQuery, 1) . '%')
				->orWhere('quote->ka', 'like', '%' . substr($searchQuery, 1) . '%')
				->offset($offset)
				->limit($perPage)
				->get();
			} else {
				$quotes = Quote::whereHas('movie', function ($query) use ($searchQuery) {
					$query->where('title->en', 'like', '%' . $searchQuery . '%')->orWhere('title->ka', 'like', '%' . substr($searchQuery, 1) . '%');
				})
				->orWhere('quote->ka', 'like', '%' . $searchQuery . '%')
				->orWhere('quote->en', 'like', '%' . $searchQuery . '%')
				->get();

				$totalPages = ceil($quotes->count() / $perPage);

				$quotes = Quote::whereHas('movie', function ($query) use ($searchQuery) {
					$query->where('title->en', 'like', '%' . $searchQuery . '%')->orWhere('title->ka', 'like', '%' . substr($searchQuery, 1) . '%');
				})
				->orWhere('quote->ka', 'like', '%' . $searchQuery . '%')
				->orWhere('quote->en', 'like', '%' . $searchQuery . '%')
				->offset($offset)
				->limit($perPage)
				->get();
			}
		}

		return response()->json(['quotes' => $quotes, 'totalpages' => $totalPages, 'currentPage' => (int)$page]);
	}

	public function store(CreateQuoteRequest $request): Quote
	{
		$attributes = $request->validated();
		$quote = ['en' => $attributes['quote_en'], 'ka' => $attributes['quote_ka']];
		$imgPath = $request->file('img')->store('public/quotes');
		$quote = ['quote' =>  $quote, 'img' => $imgPath, 'movie_id' => $attributes['movie_id'], 'user_id' => $attributes['user_id']];
		$quote = Quote::create($quote);
		return $quote;
	}

	public function update(UpdateQuoteRequest $request, $id): Quote
	{
		$attributes = $request->validated();
		$quote = ['en' => $attributes['quote_en'], 'ka' => $attributes['quote_ka']];
		$attributes = [
			'quote' => $quote,
		];
		if ($request->hasFile('img')) {
			$imgPath = $request->file('img')->store('public/quotes');
			$attributes['img'] = $imgPath;
		}
		$quote = Quote::findOrFail($id);
		$quote->update($attributes);
		return $quote;
	}

	public function destroy($id): JsonResponse
	{
		$quote = Quote::findOrFail($id);
		$quote->delete();
		return Response()->json(['msg' => 'quote deleted'], 200);
	}
}
