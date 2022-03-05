<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
   
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Post;

  

class ChangePasswordController extends Controller
{
    /**
     * Shows the home page
     *
     * @return Response
     */
    public function show(){
      return view('pages.change-password');
    }
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
    public function store(Request $request)
    {
        $this->authorize('show', Auth::user());
    
        $user = auth()->user();

        $this->authorize('show', $user);
        $posts = Post::where('id_user', '=', $user->id)->get();
   
        $noRating = array();
        $noAnswers = array();

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }

        $request->validate([
            'password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
   
        return redirect()->route('show-profile',['id' => $user->id]);
    }

  }