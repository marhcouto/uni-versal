<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Post;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function showTopics(){

        $areas_to_display = DB::table('topic')
                                ->distinct()->get('area');
                                
        
        $titles_to_display = array();  
                                     
        foreach($areas_to_display as $area){ 
            $titles_to_display[$area->area] = Topic::select('title')->where('area' ,'=', $area->area)->get()->pluck('title'); 
        }

        return $titles_to_display;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $topic = Topic::find($id_topic);
        $this->authorize('show', Topic::class);

        $questions = $topic->posts();
        $noRating = array();
        $noAnswers = array();

        foreach($questions as $question){
            $noAnswers[$question->id_post] = count($question->answers());
            
            $counter = 0;
            $post = Post::find($question->id_post);
            foreach ($post->ratings() as $user){
                if ($user->pivot->rating == 1) $counter++;
                else if ($counter > 0) $counter--;
            }
            $noRating[$question->id_post] = $counter;
        }
        

        return view('pages.topic', ['topic' => $topic, 'questions' => $questions, 'noAnswers' => $noAnswers, 'noRating' => $noRating]);
    }

    public function showTopicPage($topic){
        $this->authorize('show', Topic::class);

        $questions = QuestionController::gatherData();
        if($topic != 'Topics'){
            $questions = $questions->where('topic.title', '=', $topic);
            $area = Topic::where('title','=',$topic)->pluck('area')[0];
        }
        else $area = 'All';
        return view('/pages.topic', ['area' => $area, 'topic' => $topic, 'questions' => $questions->get(), 'topicarea' => TopicController::showTopics()]);
    }

    public function showFilteredTopicPage(Request $request, $topic) {
        $this->authorize('show', Topic::class);

        $questions = QuestionController::gatherData();
        $questions = $questions->where('topic.title', '=', $topic);
        $area = Topic::where('title','=',$topic)->pluck('area')[0];

        $filter = $request->input('filter-method');
        $ordering = $request->input('order-method');

        // Filters
        if ($filter == 'solved') {
            $questions = $questions
            ->where('solved', '=', 'True');
        } else if ($filter == 'unsolved') {
            $questions = $questions
            ->where('solved', '=', 'False');
        } else if ($filter == 'no-replies') {
            $questions = $questions
            ->whereNull('answer.id_post');
        }

        // Ordering
        if ($ordering == 'latest') {
            $questions = $questions->orderBy('date', 'desc');
        } else if ($ordering == 'popularity') {
            $questions = $questions->orderBy('no_votes', 'desc');
        } else if ($ordering == 'answers') {
            $questions = $questions->orderBy('no_answer', 'desc');
        }

        session()->flashInput($request->input());
        return view('/pages.topic', ['area' => $area, 'topic' => $topic, 'questions' => $questions->get(), 'topicarea' => TopicController::showTopics()]);
    }

}
