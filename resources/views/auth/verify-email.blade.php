@extends('layouts.app')

@section('content')
        <div class="send_email_notices">
            <h4 class="display-6 text-center fw-bold">Thank you for registering your account</h1>
            <div class="col-lg-6 mx-auto">
                <p class="text-center lead mb-4">An e-mail has been sent to the address you provided us with. Open the e-mail to conclude the registration of your account.</p>
                <form method="POST" action="{{route('verification.send')}}">
                    {{ csrf_field() }}
                    <p class="text-center lead mb-4"><button class="btn btn-primary" type="submit">Resend e-mail</button></p>
                </form>
            </div>
        </div>   
@endsection

