<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Notification;
use App\Models\Answer;

use Illuminate\Support\Facades\DB;

class NotificationController extends Controller{

    public function checkNewNotifications(){
        $this->authorize('show', Auth::user());
        $notifs = DB::table('notification')->where([['id_user', '=', Auth::user()->id], ['seen', '=', false]])->get();

        return $notifs;
    }

    public function updateNotifications(){
        $this->authorize('show', Auth::user());
        $notifications = Notification::where('id_user', '=',Auth::user()->id)->get();

        foreach($notifications as $notification){
            $notification->seen = true;
            $notification->save();
        } 
    }
    public function getNotifications(){
        $this->authorize('show', Auth::user());
        $notifications = Notification::where('id_user', '=', Auth::user()->id)->orderBy('seen', 'ASC');

        $response = view('partials.notifications.notification-list', ['notifications' => $notifications->get()])->render();

        return response()->json(['response' => $response]);
    }

    public function delete(){
        $this->authorize('show', Auth::user());
        $notifications = Notification::where('id_user', '=',Auth::user()->id)->get();
        foreach($notifications as $notification){
            $notification->delete();
        }
    }

    public function redirectNotification($notification_id){
        $this->authorize('show', Auth::user());
        $notification = Notification::Find($notification_id);

        $answer = Answer::Find($notification->id_post);
        if($answer != NULL){ //if the notification is associated with a answer
            return redirect()->route('showQuestion', $answer->id_question);
        }

        return redirect()->route('showQuestion', $notification->id_post);
    }

}

