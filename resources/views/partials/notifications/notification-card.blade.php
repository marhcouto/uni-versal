<div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
    <div class="row align-items-center">
        <div class="col-1 op-7 d-flex justify-content-center">
            @if($notification->notif_type == 'Upvote')
                <i class="ion-connection-bars icon-1x"></i>
            @else
                <i class="ion-ios-chatboxes-outline icon-1x"></i>
            @endif
        </div>
        <div class="col-10 mb-3 mb-sm-0">
            <h5>
                <a id = "post-text" href="{{route('redirect-notification', ['notification_id' => $notification->id])}}" class="text-primary">{{$notification->title}}</a>
            </h5>
            <p class="text-sm">
                <a id = "post-text" class="text-black" href="{{route('redirect-notification', ['notification_id' => $notification->id])}}">{{$notification->body}}</a> 
                <span class="op-6"></span>
                <a class="text-black" href="{{route('redirect-notification', ['notification_id' => $notification->id])}}"></a>
            </p>       
        </div>
        <div class="col-1 op-7 align-items-end">
            @if(!$notification->seen)
                <i class="bi fa-3x bi-patch-exclamation"></i>
            @endif
        </div>
    </div>
</div>