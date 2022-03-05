<div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
    <div class="row align-items-center">
        <div class="col-10 mb-3 mb-sm-0">
            <p class="text-sm">
                <a id = "post-text" class="text-black" href="{{route('redirectReport', ['id_post' => $post->id])}}">Post: {{$post->text}}</a> 
                <span class="op-6"></span>
                <a class="text-black" href="{{route('redirectReport', ['id_post' => $post->id])}}"></a>
            </p>       
        </div>
        <div class="col-2 mb-3 mb-sm-0 text-center">
            <button class="btn btn-primary openReportsModalBttn" value="{{$post->id}}" data-bs-toggle="modal" data-bs-target="#ReportsModal"> 
                <div id="numberOfReports{{$post->id}}"></div>  <i class="icon-1x bi bi-flag-fill"></i>
            </button>
        </div>
    </div>
</div>
