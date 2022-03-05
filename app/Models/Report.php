<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  public $table = 'report';
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  public $fillable = [
    'text', 'id_post', 'id_user', 'date'
  ];
  
}