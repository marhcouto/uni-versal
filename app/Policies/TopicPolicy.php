<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class TopicPolicy
{
    use HandlesAuthorization;



    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Topic $topic
     * @param  \App\Models\Topic  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function show()
    {
        return Auth::check();
    }

}

// public function delete(Post $post)
//     {
//       return Auth::check() && Auth::user()->id == $post->id_user;
//     }

//     public function update(Post $post)
//     {
//         return Auth::check() && Auth::user()->id == $post->id_user;
//     }

//     public function report(Post $post){
//       return Auth::check() && Auth::user()->id != $post->id_user;
//     }

//     public function interact()
//     {
//         return Auth::check();
//     }

//     public function draft(Post $post){
//       return Auth::check() && Auth::user()->id == $post->id_user;
//     }