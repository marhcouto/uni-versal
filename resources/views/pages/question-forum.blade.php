@extends('layouts.app')

@section('content')

@include('layouts.topics-sidenavbar')

<div class="container-sm p-4 border border-dark" id="question-forum-window">
            
    @include('partials.forum.question', ['post' => $post, 'user' => $user, 'media' => $media])

    <div class="col-3" id="number-of-answers">
        <h4 class="font-weight-bold"> {{count($answers)}} Answers </h4>
    </div>    



    @foreach ($answers as $answer)
        @include('partials.forum.answer', ['post' => $answer, 'user' => $owners[$answer->id], 'question_owner' => $user])
    @endforeach
    @include('partials.forum.create-answer', ['id_question' => $post->id])
          
</div>


@endsection