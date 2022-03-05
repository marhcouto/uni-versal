
<button id="{{$post->id}}-upvote-button" class="btn btn-primary mt-3 mb-2 upvote-button">
    <i class="icon-1x bi bi-caret-up-fill"></i>
</button> 
<span id="{{$post->id}}-noVotes-span" class="d-block">{{$post->no_votes}}</span> 
<button id="{{$post->id}}-downvote-button" class="btn btn-primary mt-3 mb-2 downvote-button">
  <i class="icon-1x bi bi-caret-down-fill"></i>
</button>

