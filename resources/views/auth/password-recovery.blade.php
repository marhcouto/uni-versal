@extends('layouts.app')

@section('content')

<div class="container login-container">
    <div class="login-form-1">  
        <h3>Password Recovery</h3>
        <form method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <input type="email" class="form-control login-form-input" id="email" name="email" placeholder="Your Email" required autofocus />
                @if ($errors->has('email'))
                    <span class="error">
                    {{ $errors->first('email') }}
                    </span>
                @endif
            </div>
            <div class="form-group" id="loginButton-container">
                <button id="loginButton-loginForm" type="submit" class="btnSubmit mt-5" value="SendRequest">Send Request</button>
            </div>

        </form>
    </div>
</div>

@endsection
