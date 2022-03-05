<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    // Actual table
    public $table = 'user';
    
    // Don't add create and update timestamps in database.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'remember_token', 'faculty', 'img', 'area', 'role', 'ban', 'permissions', 'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

   

    public function posts() {
        return $this->hasMany(Post::class, 'id_user');
    }

    
    public function isModerator(){
        return $this->permissions == 'Moderator';
    }

    public function isAdmin(){
        return $this->permissions == 'Administrator';
    }

    public function isUser(){
        return $this->permissions == 'User';
    }
        
    public function isBanned(){
        return $this->ban;
    }
        

    public function bannedBy() { // a user can be banned by a moderator
        return $this->belongsTo(Moderator::class, 'id_moderator', 'id_user');
    }
    
    public function notifications(){
        return $this->belongsToMany(Notification::class, 'seen', 'id_user', 'id_notification')->withPivotIn('seen', [0, 1]);
    }
    
    public function ratings(){
        return $this->belongsToMany(Post::class, 'rating', 'id_post', 'id_user')->withPivot('rating');
    }
    
    public function bookmarks(){
        return $this->belongsToMany(Question::class, 'bookmark', 'id_question', 'id_user');
    }
}