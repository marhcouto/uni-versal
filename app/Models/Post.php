<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  public $table = 'post';
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  public $fillable = [
    'title', 'text', 'date', 'no_votes', 'draft', 'anonymous', 'id_user'
  ];

  public function notifications() {
    return $this->hasMany(Post_notification::class, 'id_post');
  }

  public function user() {
    return $this->belongsTo(User::class, 'id_user');
  }

  public function media(){
    return $this->hasMany(Media::class);
  }

  public function ratings(){
    return $this->belongsToMany(User::class, 'rating', 'id_user', 'id_post')->withPivot('rating');
  }

  public function question(){
    return $this->belongsTo(Question::class, 'id_question');
  }

  public function answer(){
    return $this->belongsTo(Answer::class, 'id_question');
  }
}