@extends('layouts.app')

@section('content')

<div class="container login-container">
    <div class="login-form-1">  
        <h3>Password Reset</h3>
        <form method="POST" action="{{ route('password.update', ['token' => $token]) }}">
            {{ csrf_field() }}

            <div class="form-group">
                <input type="text" class="form-control login-form-input" id="token" name="token" hidden value="{{$token}}" autofocus />
            </div>


            <div class="form-group">
                <input type="email" class="form-control login-form-input" id="email" name="email" placeholder="Your Email" required autofocus />
                @if ($errors->has('email'))
                    <span class="error">
                    {{ $errors->first('email') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input name="password" type="password" class="form-control login-form-input" placeholder="Your Password" value="" required/>
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </div>

            <div class="form-group">
                <input name="password_confirmation" type="password" class="form-control login-form-input" placeholder="Confirm Password" value="" required/>
            </div>


            <div class="form-group" id="loginButton-container">
                <button id="loginButton-loginForm" type="submit" class="btnSubmit mt-5" value="Submit" formaction="{{ route('password.update') }}">Submit</button>
            </div>

        </form>
    </div>
</div>
@endsection
