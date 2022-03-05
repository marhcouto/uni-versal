@extends('layouts.app')

@section('content')

@include('layouts.topics-sidenavbar')

<div id = "homepage" class="px-4 pt-5 my-5  border-bottom">
    <h1 class="display-4 text-center fw-bold">Uni-Versal</h1>
    <div class="col-6 mx-auto">
      <p class="lead mb-4">The Q&A Forum of UPorto's Community</p>
    </div>
    <div class = "homepage-box">
          <a><h1>Post   Answer   Connect   Learn</h1></a>
    </div>
</div>  

@endsection