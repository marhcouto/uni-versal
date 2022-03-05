<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{

    /**
     * Creates a new question
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {      
        $this->authorize('create', Answer::class);
        
        if ($request->anonymous) $anonymous = true;
        else $anonymous = false;
        DB::table('post')->insert(['text' => $request->text, 'id_user' => Auth::user()->id, 'anonymous' => $anonymous]);
 
        $id = DB::table('post')->max('id');
        DB::table('answer')->insert(['id_post' => $id, 'id_question' => $request->id_question]);
  
        if ($request->hasFile('images')) {
            $images = $request->file('images');
                foreach($images as $image){
                    $destination_path =  'public/images/products';
                    // $image = $request->file('image');
                    $image_name = $image->getClientOriginalName() . $request->id_question;
                    $image->move(base_path($destination_path), $image_name);
                    DB::table('media')->insert(['url' => 'images/products/'.$image_name, 'type' => 'Image', 'id_post' => $id ]);
                }
        }
        
         return redirect()->route('showQuestion', Answer::find($id)->id_question);
    }

    /**
     * Finds an answer with the given id
     * 
     * @return App\Models\Answer
     */
    public function find($id_post) {
        return Answer::where('id_post', $id_post)->first();
    }


    /**
     * Verifies an answer (as correct)
     * 
     * @return Int
     */
    public function verify(Request $request) {
        

        $id_post = $request->input('id_post');

        $answer = Answer::where('id_post', $id_post);
        $this->authorize('verify', $answer->first());

        if ($answer->first() == null) {
            // Error
            return -1;
        }

        if ($answer->first()->verified) {
            $answer->update(['verified' => false]);
        } else {
            $answer->update(['verified' => true]);
        }

        return $id_post;
    }


    /**
     * Checks if an answer is verified
     */
    public function isVerified(Request $request, $id_post) {

        $answer = Answer::where('id_post', $id_post)->first();
        return $answer->verified;
    }
}
