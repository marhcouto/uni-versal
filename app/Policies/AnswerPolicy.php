<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Answer;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class AnswerPolicy
{
    use HandlesAuthorization;

    /**
    * Determine whether the user can create models.
    *
    * @param  \App\Models\User $user
    * @return \Illuminate\Auth\Access\Response|bool
    */
   public function create()
   {
       return Auth::check();
   }

   public function verify(User $user, Answer $answer){
        $question= Post::find($answer->id_question);
        return Auth::check() && Auth::user()->id == $question->id_user;
   }
}
