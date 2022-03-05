@extends('layouts.app')

@section('content')


<div class="container login-container">
    <div class="login-form-1">  
        <h3>Change password</h3>
        <form method="POST" action="{{ route('change.password') }}">
            @csrf 

                @foreach ($errors->all() as $error)
                    <p class="text-danger">{{ $error }}</p>
                @endforeach 

            <div class="form-group">
                <input id="password" name="password" type="password" class="form-control login-form-input" placeholder="Current Password" autocomplete="password" value="" required/>
               
            </div>
            <div class="form-group">
                <input id="password" name="new_password" type="password" class="form-control login-form-input" placeholder="New Password" autocomplete="new_password" value="" required/>
               
            </div>

            <div class="form-group">
                <input id="password-confirm" name="new_confirm_password" type="password" class="form-control login-form-input" placeholder="Confirm Password" autocomplete="new_confirm_password"value="" required/>
            </div>
            <div class="form-group" id="loginButton-container">
                <button id="loginButton-loginForm" type="submit" class="btnSubmit mt-5" value="Submit">Change</button>
            </div>
        </form>
    </div>
</div>
@endsection