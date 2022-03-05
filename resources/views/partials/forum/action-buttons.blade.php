<div class="col-1 op-7">
    <div class="row text-center op-7">  
        <div class="col px-1">

          @if (Auth::id() == $user->id || Auth::user()->permissions != 'User')
              <button class="btn btn-primary mt-3 mb-2 bookmark-button" data-bs-toggle="modal" data-bs-target="#editQuestionModal{{$post->id}}"><i class="icon-1x bi bi-pencil-square"></i></button>
              <button class="btn btn-primary mt-3 mb-2 bookmark-button" data-bs-toggle="modal" data-bs-target="#DeleteQuestionModal{{$post->id}}"><i class="icon-1x bi bi-trash-fill"></i></button>         

          @else
            <button id="openReportModal{{$post->id}}" class="btn btn-primary mt-3 mb-2 bookmark-button openReportModalBttns" value="{{$post->id}}" data-bs-toggle="modal" data-bs-target="#ReportModal{{$post->id}}"><i class="icon-1x bi bi-flag-fill"></i></button>
          @endif 
          @foreach($media[$post->id] as $pic)
            @if($pic != NULL)
              <button class="btn btn-primary mt-3 mb-2 bookmark-button" data-bs-toggle="modal" data-bs-target="#ZoomedPictureModal{{$post->id}}"><i class=" icon-1x bi bi-zoom-in"></i></button>
            @endif
          @endforeach
        </div>
    </div>
</div>


<!-- Zoomed Picture Modal -->
<div class="modal fade" id="ZoomedPictureModal{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="zoomedImageModal"aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

        <div class="modal-body">
          
          @foreach($media[$post->id] as $pic)
                <img src="{{ asset($pic->url)}}" class="d-block w-100 " alt="...">
          @endforeach

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary mt-3 mb-2 bookmark-button" data-bs-dismiss="modal">Back</button>
        </div>
    </div>
  </div>
</div>



<!-- EditQuestion Modal -->
<div class="modal fade" id="editQuestionModal{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="editQuestionModal"  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
      </div>


      <form method="POST" action="{{route('updatePost', ['id_post' => $post->id])}}" class="text-start" data-bs-toggle="validator" autocomplete="off" style="position: relative;">
                @csrf
        <div class="modal-body">
          <textarea required class="form-control mb-3" id="text" name = "text" value = "txtbx" rows="15" style="resize: none;">{{$post->text}}</textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="DeleteQuestionModal{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="DeleteQuestionModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Post</h5>
      </div>
      <form method="POST" action="{{route('deletePost', ['id_post' => $post->id])}}" class="text-start" data-bs-toggle="validator" autocomplete="off" style="position: relative;">
        @csrf
        <div class="modal-body">
          Are you sure you want to delete this post?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type = "submit" class="btn btn-primary">Yes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Report Modal -->

<div class="modal fade" id="ReportModal{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="ReportModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-5">
          <h5 class="modal-title" id="ReportModal">Report Post</h5>
        </div>
        <div id="alreadyReported{{$post->id}}"class="col-7 ml-2"></div>
      </div>
        <div class="modal-body">
          <textarea class="form-control mb-2 mt-2" id="report_details{{$post->id}}" maxlength="500" minlength="1" name ="report_details" rows="8" placeholder="Please explain the reason of the report..." style="resize: none;"></textarea>
        </div>
        <div class="modal-footer">

          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary submit-report-buttons" value="{{$post->id}}" data-bs-dismiss="modal">Report</button>
        </div>
    </div>
  </div>
</div>


<div class="modal fade" id="AlreadyReportedModal" tabindex="-1" role="dialog" aria-labelledby="ReportModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ReportModal">Report Post</h5>
      </div>
      <div class="modal-body">
          <h6>You have already reported this post. Thank you for your contribution!</h6>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>
