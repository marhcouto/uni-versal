<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\User;
use App\Models\Question;
use App\Models\Post;
use App\Models\Topic;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 
    }

    /**
     * Find a question by id
     * 
     * @return App\Models\Question;
     */
    public function find($id_post) {
        return Question::where('id_post', $id_post)->first();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCreationForm(){
        //if (!Auth::check()) return redirect('/login'); // update naming 
        $this->authorize('create', Question::class);

        $topics = (new TopicController)->showTopics();

        return view('pages.create-question',['topics' => $topics]);
    }

    /**
     * Creates a new question
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->draft){
            $validated = $request->validate([
                'title' => 'required',
                'text' => 'required',
                'topic_title' => 'required'
            ]);
        }
        $this->authorize('create', Question::class);

        if ($request->title == null && $request->text == null && $request->topic_title == null) return redirect()->route('createQuestion');

        $post = new Post();
        $post->title = $request->title;
        $post->text = $request->text;

        if($request->draft) {
            $post->draft = true;
            $post->date = null;
        }
        if ($request->anonymous) $post->anonymous = true; 
        
        $post->id_user = Auth::user()->id; 

        $post->save();

        $question = new Question();
        $question->id_post = $post->id;
        if ($request->topic_title != null) $question->id_topic = Topic::where('title', '=', $request->topic_title)->pluck('id')[0];

        
        if (!$request->draft){
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
        }
        $question->save();
      
        if ($request->draft) return redirect()->route('createQuestion');
        else return redirect()->route('showQuestion', $question->id_post);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id_post)
    {
        $this->authorize('show', Auth::user());

        $post = Post::find($id_post);
        $user = User::find($post->id_user);

        if ($post->draft) return redirect('/');

        $media = array();
        $media[$id_post] = Media::where('id_post', '=', $id_post)->get();
        $answersCollection = Answer::where('id_question', '=', $id_post);

        $answers = array();
        $owners = array();
        foreach ($answersCollection->get() as $answer){
            $answers[] = Post::find($answer->id_post);
            $owners[$answer->id_post] = User::find($answers[count($answers) - 1]->id_user);
            $media[$answer->id_post] = Media::where('id_post', '=', $answer->id_post)->get();
        }
        return view('pages.question-forum', ['post' => $post, 'answers' => $answers, 'user' => $user, 'media' => $media, 'owners' => $owners]);
    }

    

    /**
     * Function with queries to gather data
     * on questions
     *
     */

    public static function gatherData() {

        $users = DB::table('user')->select('name', 'id');

        $questions = Post::select('post.id', 'post.title', 'post.text', 'post.date', 'post.no_votes', 'post.anonymous', 'post.tsvectors', 'user.name', 'topic.area as area', 'topic.title as topic', 'user.id as id_user', DB::raw('count(answer.id_post) as no_answer'))
                        ->join('question', 'post.id', '=', 'question.id_post')
                        ->join('topic', 'topic.id', '=', 'question.id_topic')
                        ->joinSub($users, 'user',function ($join) {
                            $join->on('user.id', '=', 'post.id_user');
                        })
                        ->leftJoin('answer', 'post.id', '=', 'answer.id_question')
                        ->where('post.draft', '=', false)
                        ->groupBy('post.id', 'user.id', 'user.name', 'question.id_post', 'topic.title', 'topic.area');

        return $questions;
    }

    public function questionByTopic(Request $request, $topic) {


        $topic = Topic::where('title', '=', $topic)->get()->first()->id;
        $questions = Question::where('topic_id','=',$topic);

        return $questions;
    }
}
