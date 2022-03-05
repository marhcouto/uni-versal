@extends('layouts.app')

@section('content')


@include('layouts.topics-sidenavbar')

    <div class="container pt-1" id="search-window">
        <div class="row mb-5">
            <h3> {{$area}} > {{$topic}} </h3>
        </div>

        <div class="row">
          <form method="GET" action="{{route('showFilteredTopicPage', ['topic' => $topic])}}">
              <div class="row mb-3">
                <div class="col-9" id="filter-order-bar">

                <!-- Questions filter/order-->

                    <label class="mr-3 ml-3"> Filter By: </label>
                    <select class="custom-select custom-select-s h-100 w-auto" id="filter-method" name="filter-method">
                        <option value="none" {{ old('filter-method') == 'none' ? 'selected' : '' }}>No filter</option>
                        <option value="solved" {{ old('filter-method') == 'solved' ? 'selected' : '' }}>Solved</option>
                        <option value="unsolved" {{ old('filter-method') == 'unsolved' ? 'selected' : '' }}>Unsolved</option>
                        <option value="no-replies" {{ old('filter-method') == 'no-replies' ? 'selected' : '' }}>No replies yet</option>
                    </select>

                    <label class="mr-3 ml-3"> Order By: </label>
                    <select class="custom-select custom-select-s h-100 w-auto" id="order-method" name="order-method">
                    <option value="latest" {{ old('order-method') == 'latest' ? 'selected' : '' }}>Latest</option> 
                    <option value="popularity" {{ old('order-method') == 'popularity' ? 'selected' : '' }}>Most popular</option>
                    <option value="answers" {{ old('order-method') == 'answers' ? 'selected' : '' }}>Nº Answers</option>
                    </select>

                   

                  </div>
                  <div class="col-3">
                      <button type="submit" id="filter-button" class="btn btn-primary float-end search-window-buttons">
                          Apply
                      </button>
                  </div>
              </div>


            <hr></hr>

            
            <div id="search-window-results" class="p-4 border border-dark">            
              @each('partials.post', $questions, 'question')      
            </div>

          </form>
        </div>


    </div>



@endsection
