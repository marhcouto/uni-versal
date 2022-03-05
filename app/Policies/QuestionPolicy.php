<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\user $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create()
    {
        return Auth::check();
    }

    public function bookmark(){
        return Auth::check();
    }

    public function interact()
    {
        return Auth::check();
    }

}
