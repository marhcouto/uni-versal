@extends('layouts.app')

@section('content')


<div id = "profile-page" class="container">
    <div class="profile-main-body">
          @include('partials.profileInfo', ['user' => $user, 'votes'=> array_sum($noRating), 'answers' => array_sum($noAnswers), 'posts' => $questions->count()])
          @if($questions->count() != 0)
            <h4 style = "padding-top:30px;">Posts</h4>
            <div id = "post-container" class="container">
              <div class="row">            
                @each('partials.post', $questions, 'question')              
              </div>
            </div>
          @endif
    </div>
@endsection