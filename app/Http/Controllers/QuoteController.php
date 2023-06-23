<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
	public function index(Request $request): JsonResponse
	{
		$searchQuery = $request->input('searchQuery');
		$perPage = 6;
		$page = $request->query('page', 1);
		$offset = ($page - 1) * $perPage;

		if (!$searchQuery) {
			$quotes = Quote::orderBy('updated_at', 'desc')
							->offset($offset)
							->limit($perPage)
							->get();
			$totalPages = ceil(Quote::all()->count() / $perPage);
			return response()->json(['quotes' => $quotes, 'totalpages' => $totalPages, 'currentPage' => (int)$page]);
		}

		switch ($searchQuery[0]) {
			case '@':
				$quotesQuery = Quote::whereHas('movie', function ($subQuery) use ($searchQuery) {
					$subQuery->searchByQuote('where', substr($searchQuery, 1), 'title');
				});

				$totalQuotesCount = $quotesQuery->count();
				$totalPages = ceil($totalQuotesCount / $perPage);
				$quotes = $quotesQuery
					->offset($offset)
					->limit($perPage)
					->orderBy('updated_at', 'desc')
					->get();

				break;
			case '#':
				$quotesQuery = Quote::searchByQuote('where', substr($searchQuery, 1));

				$totalQuotesCount = $quotesQuery->count();
				$totalPages = ceil($totalQuotesCount / $perPage);
				$quotes = $quotesQuery
					->offset($offset)
					->limit($perPage)
					->orderBy('updated_at', 'desc')
					->get();

				break;
			default:
				$quotesQuery = Quote::whereHas('movie', function ($subQuery) use ($searchQuery) {
					$subQuery->searchByQuote('where', substr($searchQuery, 1), 'title');
				})->searchByQuote('orWhere', $searchQuery);

				$totalQuotesCount = $quotesQuery->count();
				$totalPages = ceil($totalQuotesCount / $perPage);
				$quotes = $quotesQuery
					->offset($offset)
					->limit($perPage)
					->orderBy('updated_at', 'desc')
					->get();
				break;
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
