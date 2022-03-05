@extends('layouts.app')

@section('content')


<div class="bg-light py-5">
  <div id = "contact-us" class="container py-6">
    <div class="row mb-4">
      <div class="col-lg-5">
        <h2 class="display-4 font-weight-light">Our team</h2>
        <p class="font-italic text-muted">4 honest students with ambition and desire to always do best!</p>
      </div>
    </div>

    <div class="row text-center">

      <div class="col-xl-3 col-sm-6 mb-5">
        <div class="bg-white rounded shadow-sm py-5 px-4"> <img src = {{ asset('images/joao.png') }} alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
          <h5 class="mb-0">João Afonso</h5><span class="small text-uppercase text-muted">CEO - FOUNDER</span>
          <ul class="social mb-0 list-inline mt-3">
            <li class="list-inline-item"><a href="https://github.com/JoaoMIEIC" class="social-link"><i class="bi bi-github"></i></a></li>
            <li class="list-inline-item"><a href="https://www.linkedin.com/in/jo%C3%A3o-afonso-andrade-182b221b9/" class="social-link"><i class="bi bi-linkedin"></i></a></li>
          </ul>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6 mb-5">
        <div class="bg-white rounded shadow-sm py-5 px-4"><img src= {{ asset('images/andre.png') }}  alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
          <h5 class="mb-0">André Santos</h5><span class="small text-uppercase text-muted">CEO - Founder</span>
          <ul class="social mb-0 list-inline mt-3">
            <li class="list-inline-item"><a href="https://github.com/andrelds11" class="social-link"><i class="bi bi-github"></i></a></li>
            <li class="list-inline-item"><a href="https://www.linkedin.com/in/andr%C3%A9-santos-a3034b213/" class="social-link"><i class="bi bi-linkedin"></i></a></li>
          </ul>
        </div>
      </div>
   
      <div class="col-xl-3 col-sm-6 mb-5">
        <div class="bg-white rounded shadow-sm py-5 px-4"><img src = {{ asset('images/sergio.png') }} alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
          <h5 class="mb-0">Sérgio Estêvão</h5><span class="small text-uppercase text-muted">CEO - Founder</span>
          <ul class="social mb-0 list-inline mt-3">
            <li class="list-inline-item"><a href="https://github.com/SergioEstevao11" class="social-link"><i class="bi bi-github"></i></a></li>
            <li class="list-inline-item"><a href="https://www.linkedin.com/in/s%C3%A9rgio-est%C3%AAv%C3%A3o-2067b41b8/" class="social-link"><i class="bi bi-linkedin"></i></a></li>
          </ul>
        </div>
      </div>

      <div class="col-xl-3 col-sm-6 mb-5">
        <div class="bg-white rounded shadow-sm py-5 px-4"><img src = {{ asset('images/marcelo.png') }} alt="" width="100" class="img-fluid rounded-circle mb-3 img-thumbnail shadow-sm">
          <h5 class="mb-0">Marcelo Couto</h5><span class="small text-uppercase text-muted">CEO - Founder</span>
          <ul class="social mb-0 list-inline mt-3">
            <li class="list-inline-item"><a href="https://github.com/marhcouto" class="social-link"><i class="bi bi-github"></i></a></li>
            <li class="list-inline-item"><a href="https://www.linkedin.com/in/marcelohcouto/" class="social-link"><i class="bi bi-linkedin"></i></a></li>
          </ul>
        </div>
      </div>


    </div>
  </div>
</div>


<div class="container my-5 py-5 z-depth-1">


  <section class="px-md-5 mx-md-5 dark-grey-text">


    <h3 class="font-weight-bold mb-4">Contact Us</h3>

    <p class="w-responsive mx-auto mb-5">You found a bug in our website? You really need help in understanding a feauture? You would like to report a strange situation?
      Or just simply give us your thoughts on the platform?
      Whatever it is, feel free to write us an email!
    </p>


    <div class="row ">


      <div class="col-md-12 mb-md-0 mb-5">

        <form method="post" action="{{route('contact')}}">
          {{ csrf_field() }}
          <div class="row">

            <div class="col-md-6">
              <div class="md-form mb-0">
                <input type="text" name="contact-name" id="contact-name" placeholder="Your name" class="form-control">
                <label for="contact-message"></label>
              </div>
            </div>

            <div class="col-md-6">
              <div class="md-form mb-0">
                <input type="text" name="contact-email" id="contact-email" placeholder="Your email" class="form-control">
                <label for="contact-message"></label>
              </div>
            </div>


          </div>

          <div class="row">

            <div class="col-md-12">
              <div class="md-form mb-0">
                <input type="text" name = "contact-subject"  id="contact-subject" placeholder ="Subject" class="form-control">
                <label for="contact-message"></label>
              </div>
            </div>

          </div>

          <div class="row">

            <div class="col-md-12">
              <div class="md-form">
                <textarea name ="contact-message" id="contact-message" placeholder ="Write your message here" class="form-control md-textarea" rows="3"></textarea>
                <label for="contact-message"></label>
              </div>
            </div>


          </div>


        <div class="text-center text-md-left">
          <button type="submit" class="btn btn-primary btn-md btn-rounded">Send</a>
        </div>

      </form>
      </div>


    </div>


  </section>

</div>
<footer class="bg-light pb-5">
  <div id = "copyrights" class="container text-center">
    <p class="font-italic text-muted mb-0">&copy; Copyrights uni-versal.com All rights reserved.</p>
  </div>
</footer> 


@endsection