<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Post;

  

class PromoteController extends Controller
{

 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_to_administrator(Request $request)
    {    
        $user_visited = User::find($request->user_id);

        $this->authorize('admin', Auth::user());
        $posts = Post::where('id_user', '=', $user_visited->id)->get();
   
        $noRating = array();
        $noAnswers = array();

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }

        $user_visited->update(['permissions'=> 'Administrator']);
   
        return redirect()->route('show-profile',['id' => $user_visited->id]);
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_to_moderator(Request $request)
    {    
        $user_visited = User::find($request->user_id);
        $this->authorize('mod', Auth::user());
        $posts = Post::where('id_user', '=', $user_visited->id)->get();
   
        $noRating = array();
        $noAnswers = array();

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }

   
        $user_visited->update(['permissions'=> 'Moderator']);
   
        return redirect()->route('show-profile',['id' =>$user_visited->id]);
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function demote_to_moderator(Request $request)
    {    
        $user_visited = User::find($request->user_id);
        $this->authorize('admin', Auth::user());
        $posts = Post::where('id_user', '=', $user_visited->id)->get();
   
        $noRating = array();
        $noAnswers = array();

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }

   
        $user_visited->update(['permissions'=> 'Moderator']);
   
        return redirect()->route('show-profile',['id' => $user_visited->id]);
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function demote_to_user(Request $request)
    {    
        $user_visited = User::find($request->user_id);
        $this->authorize('mod', Auth::user());
        $posts = Post::where('id_user', '=',$user_visited->id)->get();
   
        $noRating = array();
        $noAnswers = array();

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }

   
        $user_visited->update(['permissions'=> 'User']);
   
        return redirect()->route('show-profile',['id' => $user_visited->id]);
    }
  }