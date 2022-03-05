
<div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
    <div class="row align-items-center">
        <div class="col-8 mb-3 mb-sm-0">
            <h5>
            <a id = "post-text" href="{{route('showQuestion', ['id' => $post->id])}}" class="text-primary">{{$post->title}}</a>
            </h5>
            <p class="text-sm"><a id = "post-text" class="text-black" href="#">{{$post->body}}</a> <span class="op-6"></span> <a class="text-black" href="#"></a></p>
            <div class="text-sm op-5"> <a id = "post-text" class="text-black mr-2" href="#">#C++</a> <a id = "post-text" class="text-black mr-2" href="#">#AppStrap Theme</a> <a id = "post-text" class="text-black mr-2" href="#">#Wordpress</a> </div>
        </div>
        <div class="col-4 op-7">
            <div class="row text-center op-7">
                <div class="col px-1"> <i class="ion-connection-bars icon-1x"></i> <span class="d-block text-sm">{{$votes}}</span> </div>
                <div class="col px-1"> <i class="ion-ios-chatboxes-outline icon-1x"></i> <span class="d-block text-sm">{{$answers}}</span> </div>
                <div class="col px-1"> <button class="btn"><i class="bi bi-trash"></i><span class="d-block text-sm">Delete</span></button> </div>
            </div>
        </div>
    </div>
</div>
