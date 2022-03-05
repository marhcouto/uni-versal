<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Post;
use App\Rules\UPEmail;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function showProfile(Request $request, $id)
    {
        if (!Auth::check()) return redirect('/login');
    
        $user = User::find($id);

        $this->authorize('show', $user);
        $questions = Post::where('id_user', '=', $user->id);
        $posts = $questions->get();
        
        $noRating = array();
        $noAnswers = array(); 

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }
        
        $questions = QuestionController::gatherData()->where('post.id_user', '=', $user->id)->where(function($query) {
                $query->orWhere('post.anonymous', '=', false)->orWhere('post.id_user', '=', Auth::user()->id);
        });

        return view('/pages.profile', ['user' => $user, 'questions' => $questions->get(), 'noRating' => $noRating, 'noAnswers' => $noAnswers]);
    }

    public function showEditProfileForm(Request $request, $id) {
        return view('pages.edit-profile');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function showEditProfile($id)
    {
        if (!Auth::check()) return redirect('/login');
    
        $user = User::find($id);

        $this->authorize('edit', $user);
        $questions = Post::where('id_user', '=', $user->id);
        $posts = $questions->get();
        
        $noRating = array();
        $noAnswers = array(); 

        foreach($posts as $post){            
            $noRating[$post->id] = $post->no_votes;           
            $noAnswers[$post->id] = Answer::where('id_question', '=', $post->id)->count();
        }

        $questions = QuestionController::gatherData()->where('post.id_user', '=', $user->id)->where(function($query) {
            $query->orWhere('post.anonymous', '=', false)->orWhere('post.id_user', '=', Auth::user()->id);
        });

        return view('/pages.edit-profile', ['user' => $user, 'questions' => $questions->get(), 'noRating' => $noRating, 'noAnswers' => $noAnswers]);
    }
/**
     * Deletes a user's account
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount(Request $request, $id)
    {

        $user = User::find($id);
        Auth::logout();
        $user->delete();

        return redirect()->route('logout');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $this->authorize('edit', $user);


        $user->name = $request->name;
        $user->faculty = $request->faculty;
        $user->area = $request->area;
        $user->role = $request->role;

        if ($request->hasFile('image')) {
            $destination_path =  'public/images/products';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $image->move(base_path($destination_path), $image_name);
            $user->img = $image_name;
        }

        $user->save();
        
        return redirect()->route('show-profile', ['id' => $id]);
    }
}
