<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
  public $table = 'answer';
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $primaryKey = 'id_post';

  public $fillable = [
    'id_question'
  ];

  public function question(){
    return $this->belongsTo(Question::class, 'id_question', 'id_post');
  }

  public function post() {
    return $this->hasOne(Post::class, 'id_post', 'id');
  }

}