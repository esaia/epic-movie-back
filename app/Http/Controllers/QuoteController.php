<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Models\Quote;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with('movie')->get();
        return $quotes;
    }

    public function store(CreateQuoteRequest $request)
    {
        $attributes = $request->validated();
        $quote = ['en' => $attributes['quote_en'], 'ka' => $attributes['quote_ka']];
        $imgPath = $request->file('img')->store('public/quotes');
        $quote = ['quote' =>  $quote, 'img' => $imgPath, 'movie_id' => $attributes['movie_id'] ];
        $quote = Quote::create($quote);
        return $quote;
    }

    public function update(UpdateQuoteRequest $request, $id)
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

    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();
        return Response()->json([ 'msg' => 'quote deleted'], 200);
    }
}
