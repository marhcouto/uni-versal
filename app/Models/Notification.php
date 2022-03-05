<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  public $table = 'notification';
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  public $fillable = [
    'title', 'body', 'id_post', 'id_user', 'date', 'seen', 'notif_type'
  ];

  // public function post_notification() {
  //   return $this->hasMany(Post_notification::class, 'id_notification');
  // }

  // public function users(){
  //   return $this->belongsToMany(User::class, 'seen', 'id_notification', 'id_user')->withPivotIn('seen', [0, 1]);
  // }



}