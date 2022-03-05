<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function create(Request $request, $id_post){
        $post = Post::find($id_post);
        $this->authorize('report', $post);

        DB::table('report')->insert(['id_user' => Auth::user()->id, 'id_post' => $id_post, 'text' => $request->report_details]);
    }

    public function isReported($id_post){

        $report = DB::table('report')->where([['id_user', '=', Auth::user()->id], ['id_post', '=', $id_post]])->first();
        
        return $report;
    }


}