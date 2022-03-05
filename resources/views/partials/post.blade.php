<div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
    <div class="row align-items-center">
        <div class="col-8 mb-3 mb-sm-0">
            <div class="op-5 font-weight-bold mb-2 post-card-title"> {{$question->area}} > {{$question->topic}} </div>
            <h5>
            <a id = "post-text" href="{{route('showQuestion', ['id' => $question->id])}}" class="text-primary">{{$question->title}}</a>
            </h5>
            <p class="text-sm"><a id = "post-text" class="text-black" href="{{route('showQuestion', ['id' => $question->id])}}">{{$question->text}}</a> <span class="op-6"></span> <a class="text-black" href="#"></a></p>
            
        </div>
        <div class="col-4 op-7">
            <div class="row text-center op-7">
                <div class="col px-1"> <i class="ion-connection-bars icon-1x"></i> {{$question->no_votes}}<span class="d-block text-sm"></span> </div>
                <div class="col px-1"> <i class="ion-ios-chatboxes-outline icon-1x"></i> {{$question->no_answer}}<span class="d-block text-sm"></span> </div>
            </div>
        </div>
    </div>
</div>