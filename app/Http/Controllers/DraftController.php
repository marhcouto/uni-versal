<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DraftController extends Controller
{

    public function showDraftForm($id){
        $post = Post::find($id);
        $this->authorize('edit', $post);
        
        if (!$post->draft) return redirect()->route('showQuestion', $post->id);

        $topics = (new TopicController)->showTopics();

        $post = Post::select('post.id', 'post.title', 'post.text', 'topic.title as topic')
                        ->leftJoin('question', 'post.id', '=', 'question.id_post')
                        ->leftJoin('topic', 'topic.id', '=', 'question.id_topic')                  
                        ->where('post.id', '=', $id)->get()[0];
                        
        return view('pages.post-draft',['post' => $post, 'topics' => $topics]);

    }


    public function updateDraft(Request $request){
        if (!$request->draft){
            $validated = $request->validate([
                'title' => 'required',
                'text' => 'required',
                'topic_title' => 'required'
            ]);
        }   
        
        if ($request->title == null && $request->text == null && $request->topic_title == null) return redirect()->route('createQuestion');
        
        $post = Post::find($request->id);
        $this->authorize('edit', $post);

        $post->title = $request->title;
        $post->text = $request->text;
       
        $question = Question::find($request->id);
        if ($request->topic_title != null) $question->id_topic = Topic::where('title', '=', $request->topic_title)->pluck('id')[0];

        if(!$request->draft) {
            $post->draft = false;
            $post->date = date(DATE_ATOM);
            if ($request->anonymous) $post->anonymous = true; 
            $post->id_user = Auth::user()->id; 
        }

        if ($request->hasFile('images')) {
            $images = $request->file('images');
                foreach($images as $image){
                    $destination_path =  'public/images/products';
                    // $image = $request->file('image');
                    $image_name = $image->getClientOriginalName() . $request->id_question;
                    $image->move(base_path($destination_path), $image_name);                
                    DB::table('media')->insert(['url' => 'images/products/'.$image_name, 'type' => 'Image', 'id_post' => $question->id_post ]);
                }
        }

        $post->save();
        $question->save();
        
        if ($request->draft) return redirect()->route('createQuestion');
        else return redirect()->route('showQuestion', $question->id_post);
    }

    public function showDrafts(){
        $this->authorize('show', Auth::user());
    
        $questions = Post::select('post.id', 'post.title', 'post.text', 'topic.area as area', 'topic.title as topic')
                        ->leftJoin('question', 'post.id', '=', 'question.id_post')
                        ->leftJoin('topic', 'topic.id', '=', 'question.id_topic')                  
                        ->where('post.draft', '=', true)
                        ->where('post.id_user', '=', Auth::user()->id)
                        ->groupBy('post.id', 'question.id_post', 'topic.title', 'topic.area');

        $response = view('partials.drafts.draft-list', ['questions' => $questions->get()])->render();

        return response()->json(['response' => $response]);
    }
}