<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;



class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input("query");
    
        if (empty($query)) {
            return redirect()->back();
        }
        $exactMatchResults = Post::where('title', '=', $query)->get();
        $tsQueryTerms = array_map(function ($term) {
            return "'$term'";
        }, explode(' ', $query));
        $tsQuery = implode(' & ', $tsQueryTerms);
    
        $ftsResults = Post::selectRaw('*, ts_rank(title_tsvectors, to_tsquery(?, ?)) as rank', ['english', $tsQuery])
            ->whereRaw("title_tsvectors @@ to_tsquery('english', ?)", [$tsQuery])
            ->orWhere('title', 'ILIKE', '%' . $query . '%')
            ->orderByDesc('rank')
            ->get();
    
        $finalResults = $exactMatchResults->merge($ftsResults)->unique('id');
    
        return view('partials.searchResults', [
            'results' => $finalResults,
            'query' => $query,
        ]);
    }
    public function searchDropdown(Request $request)
{
    $query = $request->input('query');
    $suggestions = Post::select('id', 'title')
        ->selectRaw('ts_rank(title_tsvectors, to_tsquery(?, ?)) as rank', ['english', $query])
        ->whereRaw("title_tsvectors @@ to_tsquery('english', ?)", [$query])
        ->orWhere('title', 'ILIKE', '%' . $query . '%')
        ->orderByDesc('rank')
        ->take(3)
        ->get()
        ->toArray();

    return response()->json(['suggestions' => $suggestions]);
}
    
}
