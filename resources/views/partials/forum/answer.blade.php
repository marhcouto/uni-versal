<div class="row">
  <div class="col-md-12 mt-3">
    <div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
      <div class="row align-items-center">
          <div class="col-md-2 op-7">
              <div class="row text-center op-7">
                <div class="col px-1">
                 @include('partials.forum.upvote_structure', ['post' => $post])
                 
                  @if (Auth::user()->id == $question_owner->id)
                 <span class="d-block"></span> 
                  <button id='{{$post->id}}-verify-button' class="btn btn-primary mt-3 mb-2">
                    <i class="icon-1x bi bi-check2"></i>
                  </button>
                  @endif
                </div>
              </div>
          </div>
          <div class="col-md-9 mb-3 mb-sm-0">
              <h6> 
              {{$post->text}}
            </h6>                       

            <!-- NOTE: o id "carouselExampleIndicators" tem que ser mudado em toda esta pagina em cada iteração da base de dados. Ex.: id do carousel ser "carousel"+ id da answer-->

            @include('partials.forum.carousel', ['post'=> $post, 'media' => $media])

            
            <h6 class="mt-4"> 
                Date: {{substr($post->date, 0, 19)}}  
            </h6>

          </div>

          @include('partials.forum.action-buttons', ['post' => $post])

      </div>
      <div class="row align-items-center">
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
@if (Auth::user()->id == $question_owner->id)
<div name="thread-answer-data-div" style="display: none;" data_id={{$post->id}}></div>
@endif