<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
  public $table = 'question';
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $primaryKey = 'id_post';

  protected $fillable = [
    'id_topic', 'solved'
  ];

  public function topic(){
    return $this->belongsTo(Topic::class, 'id_topic');
  }

  public function answers(){
    return $this->hasMany(Answer::class, 'id_question', 'id_post');
  }

  public function bookmarks(){
    return $this->belongsToMany(User::class, 'bookmark', 'id_user', 'id_question');
  }


}