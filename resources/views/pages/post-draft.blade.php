@extends('layouts.app')

@section('content')

@include('layouts.topics-sidenavbar')

<section class="container-sm add-question card rounded-1 border border-dark" id="create-question-card" style="margin-top: 200px; position: relative;">
  <div class="row">
    <div class="col-11"><h2 class="mb-4 mt-3">Post Draft</h2></div>

    <div class="col-1 mt-3"> <button type="button" name="drafts-button" id="Draft-createQuestion" href="" class="btn btn-primary btn-block btn-register mb-3" data-bs-toggle="modal" data-bs-target="#DraftModal" >Drafts</button>
    </div>
        </div>
            <form method="POST" action="{{route('updateDraft', ['draft' => false, 'id' => $post->id])}}" class="text-start" data-bs-toggle="validator" enctype='multipart/form-data' autocomplete="off" style="position: relative;">
                @csrf

                
                    <div class="input-group mb-3">
                        
                        <select required class="form-select w-5" id = "topic_title" name = "topic_title" >
                            <option hidden selected disabled value=''>Topics</option>
                            @foreach ($topics as $area => $titles)
                                <optgroup label= "{{$area}}">
                                    @foreach ($titles as $title)
                                        @if ($title == $post->topic)
                                        <option selected value = "{{$title}}">{{$title}}</option> 
                                        @else      
                                        <option value = "{{$title}}">{{$title}}</option> 
                                        @endif                      
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        
                        <span class="input-group-addon"></span>
                        <input required class="form-control form-control-lg w-75" id = "title" name = "title" type="text" value = "{{$post->title}}" placeholder="Title"></input>
                    </div>

                
                <textarea required class="form-control mb-3" id="text" name = "text" rows="15" placeholder="Body" style="resize: none;">{{$post->text}}</textarea>
            
                <input type="checkbox" class="form-check-input pb-10" value = true id="anonymous" name = "anonymous">
                <label class="form-check-label" for="anonymous">Post anonymously</label>

                <input class="form-control form-control-sm w-50 mb-5" name = 'images[]' id="formFileSm" type="file" max-size="2000" accept="image/*" multiple>Max File Size: 2 MB </input>

                <button type="submit" class="btn btn-primary btn-block btn-register mb-3" id="addQuestion-card" value="Add Question">Post draft</button>
                <button type="submit" class="btn btn-primary btn-block btn-register mb-3" id="saveDraft-button" formaction = "{{route('updateDraft', ['draft' => true, 'id' => $post->id])}}" value="Save Draft">Update Draft</button>
                <button type = "button" class="btn btn-primary btn-block btn-register mb-3" id = "deleteDraft-button" data-bs-toggle="modal" data-bs-target="#DeleteDraftModal{{$post->id}}">Delete Draft</button>
            </form>
</section>

<div class="modal fade" id="DeleteDraftModal{{$post->id}}" tabindex="-1" role="dialog" aria-labelledby="DeleteDraftModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleDraftLabel">Delete Draft</h5>
      </div>
      <form method="POST" action="{{route('deletePost', ['id_post' => $post->id])}}" class="text-start" data-bs-toggle="validator" autocomplete="off" style="position: relative;">
        @csrf
        <div class="modal-body">
          Are you sure you want to delete this draft?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type = "submit" class="btn btn-primary">Yes</button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('partials.drafts.draft-modal')
<script type="module" src="{{ asset('js/drafts.js') }}" defer></script>

@endsection