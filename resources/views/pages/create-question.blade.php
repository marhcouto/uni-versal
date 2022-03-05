@extends('layouts.app')

@section('content')

@include('layouts.topics-sidenavbar')


<section class="container add-question card rounded-1 border border-dark" id="create-question-card" >
            <div class="row">
                <div class="col-11"><h2 class="mb-4 mt-3">Create Question</h2></div>

                <div class="col-1 mt-3"><button type="button" name="drafts-button" id="Draft-createQuestion" href="" class="btn btn-primary btn-block btn-register mb-3" data-bs-toggle="modal" data-bs-target="#DraftModal" >Drafts</button> 
                </div>
            </div>
            <form method="POST" action="{{route('addQuestion', ['draft' => false])}}" class="text-start" data-bs-toggle="validator" enctype='multipart/form-data' autocomplete="off" style="position: relative;">
                @csrf

                
                    <div class="input-group mb-3">
                        
                        <select required class="form-select w-5" id = "topic_title" name = "topic_title">
                            <option hidden selected disabled value=''>Topics</option>
                            @foreach ($topics as $area => $titles)
                                <optgroup label= "{{$area}}">
                                    @foreach ($titles as $title)
                                        <option value = "{{$title}}">{{$title}}</option>                        
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        
                        <span class="input-group-addon"></span>
                        <input required class="form-control form-control-lg w-75" id = "title" name = "title" type="text" placeholder="Title">
                    </div>

                
                <textarea required class="form-control mb-3" id="text" name = "text" rows="15" placeholder="Body" style="resize: none;"></textarea>
            
                <input type="checkbox" class="form-check-input pb-10" value = true id="anonymous" name = "anonymous">
                <label class="form-check-label" for="anonymous">Post anonymously</label>
                <div class="row">
                    <div class="col-8">
                        <input class="form-control form-control-sm w-50 mb-5" name = 'images[]' id="formFileSm" type="file" max-size="2000" accept="image/*" multiple>Max File Size: 2 MB </input>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-register mb-3" id="addQuestion-card" value="Add Question">Add Question</button>
                        <button type="submit" class="btn btn-primary btn-block btn-register mb-3" id="saveDraft-button" formaction = "{{route('addQuestion', ['draft' => true])}}" value="Save Draft">Save Draft</button>
                    </div>
                </div>
                   
            </form>
</section>

@include('partials.drafts.draft-modal')
<script type="module" src="{{ asset('js/drafts.js') }}" defer></script>


@endsection
