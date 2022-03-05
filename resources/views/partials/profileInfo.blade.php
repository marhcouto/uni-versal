<div class="row ">
    <div class="col-md-4">
        <div class="profile-card">
					<div class="profile-card-body">
							<div class="d-flex flex-column align-items-center text-center">
                                <div class = "badgesuser" style = "margin-left:auto;margin-right:0;">
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
								<div class="profile-pic2">
									<label class="-label" for="file">
										<span class="glyphicon glyphicon-camera"></span>
										<span></span>
									</label>
									<img src="{{ $user->img ? asset('images/products/'.$user->img) : asset('images/person.png')}}"alt="Profile Image" id="output" width="200"/></div>
									<div class="mt-1">
										<h3>{{$user->name}}</h3>
										<p class="text-secondary mb-2">{{$user->email}}</p>
									</div>
							</div>
					</div>
				</div>
			</div>

    <div class="col-md-8">
        <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <h6 class="mb-0">Number of upvotes:</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                    {{$votes}}
                </div>
                </div>
                <div class="row">
                <div class="col-sm-3">
                    <h6 class="mb-0">Number of posts:</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                    {{$posts}}
                </div>
                </div>
                <div class="row">
                <div class="col-sm-3">
                    <h6 class="mb-0">Number of answers:</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                    {{$answers}}
                </div>
            </div>
            <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Role:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        {{$user->role}}
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Faculty:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        {{$user->faculty}}
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Area:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        {{$user->area}}
                    </div>
                </div>
            <hr>
            <div id = "edit-btn" class="row">
                <div class="col-sm-12">
                    <form method = 'POST'>
                    {{ csrf_field() }}

                    @if (Auth::user()->isAdmin() && Auth::id() != $user->id)
                        @if($user->ban == True)
                            <button type = "submit" class="btn btn-primary " name = 'user' id = 'user' formaction =" {{ route('unban', ['user_id' => $user->id]) }}">Unban User</a>   
                        @elseif($user->permissions == "Moderator")
                            <button type = "submit" class="btn btn-primary" name = 'user' id = 'user' formaction = " {{ route('promotion-admin', ['user_id' => $user->id])}}">Promote to Administrator</a>
                            <button type = "submit" class="btn btn-primary" name = 'user' id = 'user' formaction  = " {{ route('demote-user', ['user_id' => $user->id]) }}">Demote to User</a>
                            <button type = "submit" class="btn btn-primary " name = 'user' id = 'user' formaction =" {{ route('ban', ['user_id' => $user->id]) }}">Ban User</a>    
                        @elseif( $user->permissions == "User")
                            <button type = "submit" class="btn btn-primary " name = 'user' id = 'user' formaction =" {{ route('promotion-mod', ['user_id' => $user->id]) }}">Promote to Moderator</a>
                            <button type = "submit" class="btn btn-primary " name = 'user' id = 'user' formaction ="{{ route('ban', ['user_id' => $user->id]) }}">Ban User</a>    
                        @else
                            <button type = "submit" class="btn btn-primary " name = 'user' id = 'user' formaction =" {{ route('demote-mod', ['user_id' => $user->id])}}">Demote to Moderator</a>
                            <button type = "submit" class="btn btn-primary " name = 'user' id = 'user' formaction =" {{ route('ban', ['user_id' => $user->id]) }}">Ban User</a>   
                        @endif
                    @elseif (Auth::user()->isModerator() && Auth::id() != $user->id)
                        @if($user->ban == True)
                            <button type = "submit" class="btn btn-primary " name = 'user' id = 'user' formaction =" {{ route('unban', ['user_id' => $user->id]) }}">Unban User</a>   
                        @else
                            <button type = "submit" class="btn btn-primary " name = 'user' id = 'user' formaction =" {{ route('ban', ['user_id' => $user->id]) }}">Ban User</a>   
                        @endif
                    </form>
                    @else
                    <form method = 'POST'>
                    {{ csrf_field() }}
                        <a class="btn btn-primary " href=" {{ route('edit-profile', ['id' => Auth::id()]) }} ">Edit Profile</a>
                        <a class="btn btn-primary " href=" {{ route('password') }}">Change Password</a>
                        <button type = "button" class="btn btn-danger btn-block btn-register " id = "deleteAccount-button" data-bs-toggle="modal" data-bs-target="#DeleteAccountModal{{Auth::id()}}">Delete Account</button>
                        <!-- <button type = "submit" class="btn btn-danger " formaction = " {{ route('delete-account', ['id' => Auth::id()])}}">Delete Account</button> -->
                    @endif
                    </form>
                </div>
                
            </div>
        </div>
        </div>
    </div>
</div>



<div class="modal fade" id="DeleteAccountModal{{Auth::id()}}" tabindex="-1" role="dialog" aria-labelledby="DeleteAccountModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleDraftLabel">Delete Account</h5>
      </div>
      <form method="POST" class="text-start" data-bs-toggle="validator" autocomplete="off" style="position: relative;">
        @csrf
        <div class="modal-body">
          Are you sure you want to delete your account?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
          <button type = "submit" class="btn btn-danger " formaction = " {{ route('delete-account', ['id' => Auth::id()])}}">Yes</button>
        </div>
      </form>
    </div>
  </div>
</div>
