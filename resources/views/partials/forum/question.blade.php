<div id="question-thread" class="row">
  <div class="col-md-12">
    <div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
      <div class="row align-items-center">
          <div class="col-md-2 op-7">
              <div class="row text-center op-7">
                <div class="col px-1">
                  @include('partials.forum.upvote_structure', ['post' => $post])
                </div>
                <span class="d-block"><i id="{{$post->id}}-solved-icon" style="visibility: hidden;" class="fa-4x bi bi-check icon-green"></i></span> 
              </div>
              <div class="row text-center op-7">                     
                <div class="col px-1">
                  <button id="{{$post->id}}-bookmark-button" class="btn btn-primary mt-3 mb-2 bookmark-button"><i class="icon-1x bi bi-star-fill"></i></button>
                </div>
              </div>
          </div>
          <div class="col-md-9 mb-3 mb-sm-0">
            <div class="row">
              <h3 id = "post-text" class="text-primary" data-id='{{$post->id}}'>
                {{$post->title}}
              </h3>
              
              <hr></hr>

              <h6 class="mt-4"> 
                {{$post->text}}

              </h6>

              @include('partials.forum.carousel', ['post' => $post, 'media' => $media])

              <h6 class="mt-4"> 
                Date: {{substr($post->date, 0, 19)}}    
              </h6>
            </div>
            <div class="row">
            </div>                                                     
          </div>

            @include('partials.forum.action-buttons', ['post' => $post])
                    
          <div class="w-100"></div>
          
          <div class="col-md-9 mb-3 mb-sm-0 text-end">
                  <!-- if no profile pic-->
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                  <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                  </svg>
          </div>

          <div class="col-md-2 mb-3 mb-sm-0">
              @if ($post->anonymous)
                <label style="text-decoration:none;"><h5> Anonymous </h5></label>
              @else
                <a href="{{route('show-profile', ['id' => $user->id])}}" style="text-decoration:none;"><h5> {{$user->name}} </h5></a>
              @endif
          </div>
      </div>
    </div>
  </div>
</div>
<div name="thread-post-data-div" style="display: none;" data_id={{$post->id}} ></div>
<div id="thread-question-data-div" style="display: none;" data_id={{$post->id}} ></div>
