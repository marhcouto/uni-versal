<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BookmarkController extends Controller{


    public function bookmark(Request $request){
        $this->authorize('bookmark', Question::class);
        
        $question = Question::find($request->input('id_post'));

        $bookmark = DB::table('bookmark')->where([['id_question', '=', $question->id_post], ['id_user', '=', Auth::user()->id]]);

        if ($bookmark->count() == 0) DB::table('bookmark')->insert(['id_user' => Auth::user()->id, 'id_question' => $question->id_post]);
        else DB::table('bookmark')->where([['id_question', '=', $question->id_post], ['id_user', '=', Auth::user()->id]])->delete();

        return ["id_post" => $request->input('id_post')];
    }

    public function getBookmark(Request $request, $id_post) {

        $question= Question::find($id_post);

        $bookmark = DB::table('bookmark')->where([['id_question', '=', $question->id_post], ['id_user', '=', Auth::user()->id]])->first();

        return ["bookmark" => $bookmark, "id_post" => $id_post];
    }


    public function showBookmarks(){
        $this->authorize('show', Auth::user());

        $questions = Post::select('post.id', 'post.title', 'post.text', 'topic.area as area', 'topic.title as topic')
                        ->leftJoin('question', 'post.id', '=', 'question.id_post')
                        ->leftJoin('topic', 'topic.id', '=', 'question.id_topic')
                        ->leftJoin('bookmark', 'bookmark.id_question', '=', 'post.id')
                        ->where('bookmark.id_user', '=', Auth::user()->id)            
                        ->groupBy('post.id', 'question.id_post', 'topic.title', 'topic.area');

        
        $response = view('partials.bookmarks.bookmark-list', ['questions' => $questions->get()])->render();

        return response()->json(['response' => $response]);
    }
}