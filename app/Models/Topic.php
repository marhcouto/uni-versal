<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
  public $table = 'topic';
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  public $fillable = [
    'title', 'area'
  ];


  public function questions(){
    return $this->hasMany(Question::class, 'id_question')->where('draft', '=', false);
  }
}