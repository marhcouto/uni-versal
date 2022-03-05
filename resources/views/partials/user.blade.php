<div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
    <div class="row align-items-center">
        <div class="col-1 mb-3 mb-sm-0">
            <div class="list-user-pic">
                <img src="{{ $user->img ? asset('images/products/'.$user->img) : asset('images/person.png')}}"alt="Profile Image" id="output" width="200"/>
            </div>
		</div>
        <div class="col-2 mb-3 mb-sm-0">
            <a href="{{route('show-profile', ['id' => $user->id])}}"style="text-decoration:none;"><h5> {{$user->name}} </h5></a>
        </div>
        <div class="col-3 op-7">
            <div class="row align-items-start">Role: {{$user->role}}</div>
            <div class="row align-items-start">Faculty: {{$user->faculty}}</div>
            <div class="row align-items-start">Area: {{$user->area}}</div>
        </div>
        <div class="col-5 ">
            <div class = "badgesuser" style = "margin-left:0;margin-right:auto;float:right;">
                @if($user->ban == True)
                    <span class="badge bg-danger pull-left">Banned</span>
                @elseif($user->permissions == "Moderator")
                    <span class="badge bg-success">Moderator</span>
                @elseif( $user->permissions == "User")
                    <span class="badge bg-primary">User</span>
                @else
                    <span class="badge bg-warning text-dark">Admin</span>
                @endif
            </div>
        </div>
    </div>
</div>