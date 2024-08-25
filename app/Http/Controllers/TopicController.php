<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Topic;

class TopicController extends Controller
{
    function showTopicNews($id){
    
        $posts= Post::where('id_topic', $id)->orderBy('date', 'desc')->get();
        $topic = Topic::find($id);
        return view('pages.newsbytopic', ['posts' => $posts,'topic'=>$topic]);
    }

    function showAddTopic() {
        return view('pages.proposeTopic');
    }

    function addTopic(Request $request) {
        $request->validate([
            'title' => 'required|string|max:100|unique:topic',
            'description' => 'max:250',
        ], [
            'title.required' => '❌ The title field is required.',
            'title.string' => '❌ The title must be a string.',
            'title.max' => '❌ The title must not exceed 100 characters.',
            'title.unique' => '❌ The title has already been taken.',
            'description.max' => '❌ The description must not exceed 250 characters.',
        ]);
        
        Topic::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => 'Pending',
        ]);

        return redirect()->back()->with('success', '✅ Your topic will be revised an added if we see it is fit to FEUP Times');
    }

    function showTopicManagement() {
        $topics = Topic::orderBy('id')->paginate(5);
        return view('pages.topicManagement', ['topics' => $topics]);
    }

    function deleteTopic($id) {
        $topic = Topic::find($id);
        $topic->delete();

        return redirect()->back()->with('success', '✅ Topic deleted succesfully');
    }

    function acceptTopic($id) {
        $topic = Topic::find($id);
        $topic->type = 'Accept';
        $topic->save();
        return redirect()->back()->with('success', '✅ Topic added succesfully');
    }

    function searchTopic(Request $request) {
        $query = $request->input('title');
        if (empty($query)) {
            return redirect()->back();
        }
        $tsQueryTerms = array_map(function ($term) {
            return "'$term'";
        }, explode(' ', $query));
        $tsQuery = implode(' & ', $tsQueryTerms);
        $exactMatchResults = Topic::where('title', 'ILIKE', $query);
        $ftsResults = Topic::whereRaw("title_tsvectors @@ to_tsquery('english', ?)", [$tsQuery])
            ->orWhere('title', 'ILIKE', '%' . $query . '%');
        
        $finalResults = $exactMatchResults->union($ftsResults)->paginate(5);
    
        return view('pages.topicManagement', ['topics' => $finalResults]);
    }
}
