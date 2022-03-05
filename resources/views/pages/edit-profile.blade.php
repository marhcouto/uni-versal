@extends('layouts.app')

@section('content')

<div id = "editprofile-container" class="container">
		<div id = "editprofile-main-body" class="main-body">
			@include('partials.editProfileInfo', ['user' => $user])

      @if($questions->count() != 0)
        <h4>Posts</h4>
        <div id = "post-container" class="container">
          <div class="row">
            @each('partials.post', $questions, 'question')  
          </div>
        </div>
      @endif
    
    </div>
</div>

@endsection