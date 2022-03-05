@extends('layouts.app')

@section('content')

<div class="container login-container">
    <div class="login-form-1">  
        <h3>Login</h3>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <input type="email" class="form-control login-form-input" id="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required autofocus />
                @if ($errors->has('email'))
                    <span class="error">
                    {{ $errors->first('email') }}
                    </span>
                @endif
            </div>
            <div class="form-group">
                <input id="password" name="password" type="password" class="form-control login-form-input" placeholder="Your Password" value="" required/>
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </div>
            <div class="form-group" id="loginButton-container">
                <button id="loginButton-loginForm" type="submit" class="btnSubmit " value="Login">Login</button>
            </div>
            <div class="form-group" id="forgotPwdButton-container">
                <a href =" {{route('password.request')}}" id="forgotpassword-loginForm" class="ForgetPwd ">Forgot Password?</a>
            </div>
        </form>
    </div>
</div>

@endsection
