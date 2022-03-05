@extends('layouts.app')

@section('content')


<section class="about-us py-5 " id="about-us">
    <div id = "about-section"  class="container mt-5">
		<div class="row">
			<div class="col-md-6">
      <h1 >Unfortunately im afraid you have been banned!</h1>
				<hr>
				<p>Since you're banned you will not be able to access our website and you also lost all the priviliges that you had before.</p>
				<p>If you do not agree with your ban or you just simply want to know what were the reasons behind it feel free to contact us down below!</p>
      
			</div>
			<div id = "about-image" class="col-md-6">
				<img src= {{ asset('images/banned3.png') }} alt="">
			</div>
		</div>
	</div>
</section>

<div class="container my-5 py-5 z-depth-1">


  <section class="px-md-5 mx-md-5 dark-grey-text">


    <h3 class="font-weight-bold mb-4">Contact Us</h3>

    <p class="w-responsive mx-auto mb-5">You wanna know the reasons behind your ban? You feel like it was not justified? Feel free to contact us!
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
@endsection