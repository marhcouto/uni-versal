<script>
var loadFile = function(event) {
	var image = document.getElementById('output');
	image.src = URL.createObjectURL(event.target.files[0]);
};
</script>

<form method="POST" action="{{route('update-profile', ['id' => Auth::id()])}}"  enctype='multipart/form-data'>
{{ csrf_field() }}
	<div class="row">
			<div class="col-lg-4">
			@php
				if (isset($user->img))
					$image_path = 'images/products/'.$user->img;
				
				else
					$image_path = 'images/person.png';
			@endphp
				<div class="edit-card">
					<div class="card-body">
							<div class="d-flex flex-column align-items-center text-center">
								<div class="edit-profile-pic">
									<label class="-label" for="file">
										<span class="glyphicon glyphicon-camera"></span>
										<span>Change Image</span>
									</label>
									<input id="file" name = "image" type="file" onchange="loadFile(event)"/>
									<img src="{{ asset($image_path)}}" alt="Profile Image" id="output" width="200"/></div>
									<div class="mt-4">
										<h3>{{$user->name}}</h3>
										<p class="text-secondary mb-3">{{$user->email}}</p>
									</div>
							</div>
					</div>
				</div>
			</div>

			<div class="col-lg-8">
				<div class="card">
						<div class="card-body">
							<div class="row mb-2">
								<div class="col-sm-2">
									<h6 class="mb-0">Name</h6>
								</div>
								<div class="col-sm-9 text-secondary">
									<input type="text" class="form-control" id = "name" name = "name" value='{{$user->name}}' required>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-sm-2">
									<h6 class="mb-0">Email</h6>
								</div>
								<div class="col-sm-9 text-secondary">
									<input type="email"  class="form-control"  id = "email" name = "email"  disabled="disabled" value='{{$user->email}}'>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-sm-2">
									<h6 class="mb-0">Role</h6>
								</div>
								<div class="col-sm-9 text-secondary">
									<select class="form-select" id = "role" name = "role" aria-label="Default select example">
										@if ($user->role === 'Professor')
											<option value="Professor" selected>Professor</option>
											<option value="Student">Student</option>
										@else
											<option value="Professor">Professor</option>
											<option value="Student"selected>Student</option>
										@endif
									</select>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-sm-2">
									<h6 class="mb-0">Faculty</h6>
								</div>
								<div class="col-sm-9 text-secondary">
									<input type="text" class="form-control"  id = "faculty" name = "faculty" value='{{$user->faculty}}'>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-sm-2">
									<h6 class="mb-0">Area</h6>
								</div>
								<div class="col-sm-9 text-secondary">
									<input type="text" class="form-control"  id = "area" name = "area" value='{{$user->area}}'>
								</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-5"></div>
								<div id = "save-btn"  class="col-sm-8 text-secondary">
									<button type="submit" class="btn btn-primary"  value="Save Changes">Save Changes</button>
								</div>
							</div>
						</div>
					
			</div>
	</div>			
</div>
</form>	