@extends('layouts.app')

@section('content')

<section class="about-us py-5 " id="about-us">
    <div id = "about-section"  class="container mt-5">
		<div class="row">
			<div class="col-6">
				<h1 >Welcome to our website!</h1>
				<h2>Who are we?</h2>
				<hr>
				<p>We are 4 Portuguese students and we are currently studying at FEUP( Engineering Faculty of Porto ).</p>
				<p>We built and developed this website as a project for one of our courses and our main objective was to be able to create a friendly and easy-going environment where students and professors from the University of Porto are able to interact between themselves.</p>
				<p>Here you will not only be able to help other coleagues but share with the others your own personal issues or doughts that you might have.</p>
				<p>We are very happy to have you here and we wish you the best of luck!</p>

			</div>
			<div id = "about-image" class="col-6">
				<img src= {{ asset('images/hat.png') }} alt="">
			</div>
		</div>
	</div>
</section>


@endsection