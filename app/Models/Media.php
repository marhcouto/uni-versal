<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
  public $table = 'media';
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  public $fillable = [
    'url', 'type', 'id_post'
  ];


  public function post(){
    return $this->belongsTo(Post::class, 'id_post');
  }




}