<div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
    <div class="row align-items-center">
        <div class="col-10 mb-3 mb-sm-0">
            @if ($question->topic != null)
            <div class="op-5 font-weight-bold mb-2 post-card-title"> {{$question->area}} > {{$question->topic}} </div>
            @else
            <div class="op-5 font-weight-bold mb-2 post-card-title"> Sem tópico </div>
            @endif
            <h5>
            @if ($question->title != null)
            <a id = "post-text" href="{{route('showDraftForm', ['id' => $question->id])}}" class="text-primary">{{$question->title}}</a>
            @else
            <a id = "post-text" href="{{route('showDraftForm', ['id' => $question->id])}}" class="text-primary">Sem título</a>
            @endif
            </h5>
            @if ($question->text != null)
            <p class="text-sm"><a id = "post-text" class="text-black" href="{{route('showDraftForm', ['id' => $question->id])}}">{{$question->text}}</a> <span class="op-6"></span> <a class="text-black" href="#"></a></p>
            @else
            <p class="text-sm"><a id = "post-text" class="text-black" href="{{route('showDraftForm', ['id' => $question->id])}}">Sem texto</a> <span class="op-6"></span> <a class="text-black" href="#"></a></p>
            @endif
        </div>
    </div>
</div>
