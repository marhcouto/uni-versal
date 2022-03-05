@extends('layouts.app')

@section('content')


@include('layouts.topics-sidenavbar')

    <div class="container-sm pt-1" id="search-window">
        <div class="row mb-5">
            <div class="col">
                @if ($mode == 'question')
                    <a href=" {{route('search-questions', ['baseInput' => $baseInput])}} " autofocus type="button" id="search-question-btn" class="btn btn-primary search-window-buttons border-dark font-weight-bold" style="color: gold;">
                        Questions
                    </a>
                    <a href=" {{route('search-users', ['baseInput' => $baseInput])}} " type="button" id="search-profiles-btn" class="btn btn-primary search-window-buttons border-dark font-weight-bold">
                        Profiles
                    </a>
                @else
                    <a href=" {{route('search-questions', ['baseInput' => $baseInput])}} " type="button" id="search-question-btn" class="btn btn-primary search-window-buttons border-dark font-weight-bold">
                        Questions
                    </a>
                    <a href=" {{route('search-users', ['baseInput' => $baseInput])}} " autofocus type="button" id="search-profiles-btn" class="btn btn-primary search-window-buttons border-dark font-weight-bold" style="color: gold;">
                        Profiles
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
          @if ($mode == 'question')
            @include('partials.search.questions-filters-orders', ['baseInput' => $baseInput])
          @elseif($mode == 'user')
            @include('partials.search.profiles-filters', ['baseInput' => $baseInput])
          @endif

          <div class="row">
              <div class="ml-3 searchedText">Searched for: "{{ $baseInput }}"</div>
          </div>  

            <hr></hr>

            
            <div id="search-window-results" class="p-4 border border-dark">
              @if ($mode == 'question')
                @each('partials.post', $questions, 'question')
              @elseif ($mode == 'user')
                @each('partials.user', $users, 'user')
              @endif          
            </div>


        </div>


    </div>



@endsection
