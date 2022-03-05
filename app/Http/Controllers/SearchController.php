<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;



class SearchController extends Controller {

    /**
     * Retrieve the search results page with results
     * after a usage of the search bar
     *
     * @return \Illuminate\Http\View
     */
    public function search(Request $request) {

        return redirect()->route('search-questions', ['baseInput' => $request->input('search-input')]);
    }

    /**
     * Retrieve the search results page with results
     * after a change to the questions section
     *
     * @return \Illuminate\Http\View
     */
    public function questions($baseInput) {
        $this->authorize('show', Topic::class);

        $filteredInput = $this->injectionDefender($baseInput);

        $questions = $this->questionSearch($filteredInput);
        $questions = $questions->orderByRaw("ts_rank(tsvectors,to_tsquery('simple',?)) DESC", [$filteredInput]); // Order by relevance

        return view('pages.search', ['topicarea' => TopicController::showTopics(), 'mode' => 'question', 'questions' => $questions->get(), 'baseInput' => $baseInput]);
    }

    /**
     * Retrieve the search results page with results
     * after a change to the users section
     *
     * @return \Illuminate\Http\View
     */
    public function users($baseInput) {
        $this->authorize('show', Topic::class);

        
        $filteredInput = $this->injectionDefender($baseInput);

        $users = $this->userSearch($filteredInput);
        $users = $users->orderByRaw("ts_rank(tsvectors,to_tsquery('simple',?)) DESC", [$filteredInput]); // Order by relevance

        return view('pages.search', ['topicarea' => TopicController::showTopics(), 'mode' => 'user', 'users' => $users->get(), 'baseInput' => $baseInput]);
    }

    /**
     * Remove improper characters in order to
     * dodge possible sql injection attacks
     *
     * @return String
     */
    public function injectionDefender($input) {

        return str_replace(' ',' | ', preg_replace("/[^0-9a-zA-ZÀ-ú\s]/", "", trim($input)));
    }


    /**
     * Search the database using a sentence/word
     * for question matches using fts
     * 
     * @return Query
     */
    public function questionSearch($input) {

        return QuestionController::gatherData()->whereRaw("tsvectors @@ plainto_tsquery('simple',?)", [$input]);
    }


    /**
     * Search the database using a sentence/word
     * for user matches using fts
     * 
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function userSearch($input) {

        if (Auth::user()->isAdmin() || Auth::user()->isModerator()) {
            return User::whereRaw("tsvectors @@ plainto_tsquery('simple',?)", [$input]);
        }
        return User::whereRaw("tsvectors @@ plainto_tsquery('simple',?)", [$input])->where('ban',false);

    }

    /**
     * Filter/Order users and return search-results page
     * 
     * @return \Illuminate\Http\View
     */
    public function showFilteredUsers(Request $request, $baseInput) {
        $this->authorize('show', Topic::class);

        $filteredInput = $this->injectionDefender($baseInput);
        $users = $this->userSearch($filteredInput);

        $filterRole = $request->input('filter-method-role');
        $filterRank = $request->input('filter-method-rank');

        if ($filterRole == 'professor') {
            $users = $users->where('role','=','Professor');
        } else if ($filterRole == 'student') {
            $users = $users->where('role', '=', 'Student');
        }

        if ($filterRank == 'user') {
            $users = $users->where('permissions','=','User');
        } else if ($filterRank == 'moderator') {
            $users = $users->where('permissions','=','Moderator');
        } else if ($filterRank == 'admin') {
            $users = $users->where('permissions','=','Administrator');
        }

        session()->flashInput($request->input());
        return view('pages.search', ['mode' => 'user', 'users' => $users->get(), 'baseInput' => $baseInput]);
    }

    /**
     * Filter/Order questions and return search-results page
     * 
     * @return \Illuminate\Http\View
     */
    public function showFilteredQuestions(Request $request, $baseInput) {
        $this->authorize('show', Topic::class);

        $filteredInput = $this->injectionDefender($baseInput);
        $questions = $this->questionSearch($filteredInput);
        $filter = $request->input('filter-method');
        $ordering = $request->input('order-method');
        $orderWay = $request->input('order-way');
        $topic = $request->input('select-topics-navbar');

        // Topics
        if ($topic != 'all-topics') {
            $questions = $questions
            ->where('topic.title', '=', $topic);
        } 

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
            $questions = $questions->orderBy('date', $orderWay);
        } else if ($ordering == 'popularity') {
            $questions = $questions->orderBy('no_votes', $orderWay);
        } else if ($ordering == 'relevance') {
            $questions = $questions->orderByRaw("ts_rank(tsvectors,to_tsquery('simple',?)) " . $orderWay , [$filteredInput]);
        } else if ($ordering == 'answers') {
            $questions = $questions->orderBy('no_answer', $orderWay);
        }

        session()->flashInput($request->input());
        return view('pages.search', ['topicarea' => TopicController::showTopics(), 'mode' => 'question', 'questions' => $questions->get(), 'baseInput' => $baseInput]);
    }



}