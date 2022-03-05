<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    use HandlesAuthorization;


    public function edit(User $user, Post $post)
    {
        return $user->id == $post->id_user;
    }

    public function report(User $user, Post $post){
        return $user->id != $post->id_user;
    }

}
