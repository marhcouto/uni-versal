<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Report;
use App\Models\Post;
use App\Models\Answer;
use App\Models\Question;

use Illuminate\Support\Facades\DB;

class ModeratorController extends Controller
{
  
    /**
     * Filter/Order users and return search-results page
     * 
     * @return \Illuminate\Http\View
     */
    public function showReports() {
        $this->authorize('mod', Auth::user());

        $posts = DB::table('report')
                ->leftJoin('post','post.id','=','report.id_post')->distinct()
                ->get(['id', 'post.text']);

        return view('pages.moderator-page',['mode' => 'reports', 'posts' => $posts]);
    }

    public function redirectReport($id_post){

        $answer = Answer::Find($id_post);
        if($answer != NULL){ //if the notification is associated with a answer
            return redirect()->route('showQuestion', $answer->id_question);
        }
        return redirect()->route('showQuestion', $id_post);
    }

    /**
     * Filter/Order users and return search-results page
     * 
     * @return \Illuminate\Http\View
     */
    public function showBannedUsers() {
        $this->authorize('mod', Auth::user());

        $users = DB::table('user')
                ->join('banned','banned.id_user','=','user.id')
                ->get();
        $users = $users->where('id_moderator','=',Auth::id());

        return view('pages.moderator-page',['mode' => 'users', 'users' => $users]);
    }

    public function getPostReports($id_post){
        $reports = Report::where('id_post', '=', $id_post);
        $response = view('partials.reports.report-list', ['reports' => $reports->get()])->render();

        return response()->json(['response' => $response]);
    }

    public function getNumReports($id_post){
        $reports = Report::where('id_post', '=', $id_post)->get(['id_post']);
        return $reports;
    }

  }