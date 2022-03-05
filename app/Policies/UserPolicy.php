<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function show(User $profile)
    {
        return Auth::check() && !$profile->ban;
    }

    public function interact(){
        return Auth::check();
    }

    public function edit(User $profile){
        return Auth::check() && Auth::user()->id == $profile->id;
    }

    public function mod(User $mod){
        return $mod->user_permissions != 'User';
    }
  
    public function admin(User $mod){
        return $mod->user_permissions != 'User' && $mod->user_permissions != 'Moderator';
    }
  
}
