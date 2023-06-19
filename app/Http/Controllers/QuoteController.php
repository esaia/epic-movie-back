<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuoteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = 6;
        $page = $request->query('page', 1);
        $offset = ($page - 1) * $perPage;

        $totalQuotes = Quote::all()->count();
        $maxPage = ceil($totalQuotes / $perPage);


        $quotes = Quote::orderBy('updated_at', 'desc')
                        ->offset($offset)
                        ->limit($perPage)
                        ->get();


        return response()->json(['quotes' => $quotes, 'maxPage' => $maxPage]);
    }

    public function store(CreateQuoteRequest $request): Quote
    {
        $attributes = $request->validated();
        $quote = ['en' => $attributes['quote_en'], 'ka' => $attributes['quote_ka']];
        $imgPath = $request->file('img')->store('public/quotes');
        $quote = ['quote' =>  $quote, 'img' => $imgPath, 'movie_id' => $attributes['movie_id'],'user_id' => $attributes['user_id'] ];
        $quote = Quote::create($quote);
        return $quote;
    }

    public function update(UpdateQuoteRequest $request, $id): Quote
    {
        $attributes = $request->validated();
        $quote = ['en' => $attributes['quote_en'], 'ka' => $attributes['quote_ka']];
        $attributes = [
            'quote' => $quote
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
        return Response()->json([ 'msg' => 'quote deleted'], 200);
    }
}
