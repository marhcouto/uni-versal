<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {      
        $validated = $request->validate([
            'text' => 'required'   
        ]);

        $answer = Answer::where('id_post', '=', $request->id_post);

        $post = Post::find($request->id_post);

        $this->authorize('edit', $post);

        $post->text = $request->text;       
        $post->save();

        if ($answer->count() == 0) return redirect()->route('showQuestion', $request->id_post); 
        return redirect()->route('showQuestion', $answer->pluck('id_question')[0]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function delete($id_post)
    {
        $post = Post::find($id_post);

        $this->authorize('edit', $post);

        $question = Question::where('id_post', '=', $id_post);

        if ($question->count() == 0) {
            $id_question = Answer::find($id_post)->id_question;

            $post->delete();
            
            return redirect()->route('showQuestion', $id_question);
        }
        else {
            Post::join('answer', 'post.id', '=', 'answer.id_post')->where('id_question', '=', $id_post)->delete();
            $post->delete();

            return redirect()->route('home');
        }
    }

    /**
     * Upvote/Downvote/Cancelvote and return 
     * number of upvotes of the post
     * 
     * @param  \Illuminate\Http\Request
     * @return Int 
     */
    public function vote(Request $request){
        $post = Post::find($request->input('id_post'));

        $vote = $request->input('vote') == 'true' ? True : False;


        $rating = DB::table('rating')->where([['id_post', '=', $post->id], ['id_user', '=', Auth::user()->id]]);

        
        if ($rating->count() == 0)
            DB::table('rating')->insert(['id_user' => Auth::user()->id, 'id_post' => $post->id, 'rating' => $vote]);
        else {
            if (($vote == $rating->pluck('rating')[0])) DB::table('rating')->where([['id_post', '=', $post->id], ['id_user', '=', Auth::user()->id]])->delete();
            else DB::table('rating')->where([['id_post', '=', $post->id], ['id_user', '=', Auth::user()->id]])->update(['rating' => $vote]);
        }

        return ["no_votes" => Post::find($request->input('id_post'))->no_votes, "id_post" => $request->input('id_post')];
    }


    /**
     * Return a rating associated with
     * a post and a user
     * 
     * @param \Illuminate\Http\Request
     * @param \App\Models\Question
     * @return Rating
     */
    public function getRating(Request $request, $id_post) {

        $post = Post::find($id_post);

        $rating = DB::table('rating')->where([['id_post', '=', $post->id], ['id_user', '=', Auth::user()->id]])->first();

        return ["rating" => $rating, "id_post" => $id_post];
    }



}
