@extends('layouts.app')

@section('content')
        <div class="send_email_notices">
            <h4 class="display-6 text-center fw-bold">Password reset in progress</h1>
            <div class="col-lg-6 mx-auto">
                <p class="text-center lead mb-4">An e-mail has been sent to the address you provided us with. Open the e-mail to conclude the password reset process.</p>
                <form method="POST" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    <p class="text-center lead mb-4"><button class="btn btn-primary" type="submit">Resend e-mail</button></p>
                </form>
            </div>
        </div>   
@endsection