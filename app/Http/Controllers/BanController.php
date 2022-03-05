<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
   
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Post;
use Illuminate\Support\Facades\DB;


  

class BanController extends Controller
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
     * Shows the banned page
     *
     * @return View
     */
    public function show(){
        return view('pages.banned');
    }
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function ban_user(Request $request)
    {    
        $this->authorize('mod', User::class);

        $user_visited = User::find($request->user_id);
        
        $posts = Post::where('id_user', '=', $user_visited->id)->get();
   
        $noRating = array();
        $noAnswers = array();

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }

        $user_visited->update(['ban'=> True]);

        DB::table('banned')->insert(['id_user' => $user_visited->id,'id_moderator' => Auth::id()]);

   
        return redirect()->route('show-profile',['id' => $user_visited->id]);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function unban_user(Request $request)
    {    
        $this->authorize('mod', Auth::user());

        $user_visited = User::find($request->user_id);

        $posts = Post::where('id_user', '=', $user_visited->id)->get();
   
        $noRating = array();
        $noAnswers = array();

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }

        $user_visited->update(['ban'=> False]);

        $banned = DB::table('banned')->where('id_user','=',$user_visited->id)->delete();
   
        return redirect()->route('show-profile',['id' => $user_visited->id]);
    }
  }