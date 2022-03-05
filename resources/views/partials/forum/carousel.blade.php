@if (count($media[$post->id]) > 0)
  <div id= "carouselIndicators{{$post->id}}" class="carousel carousel-post slide mt-4 border" data-bs-ride="carousel">             
  <div class="carousel-indicators">
      <?php $counter = 0 ?>
      @foreach($media[$post->id] as $pic)
        @if ($counter == 0)
          <button type="button" data-bs-target="#carouselIndicators{{$post->id}}" data-bs-slide-to="{{$counter}}" class="active" aria-current="true" aria-label="Slide"></button>
        @else
          <button type="button" data-bs-target="#carouselIndicators{{$post->id}}" data-bs-slide-to="{{$counter}}" aria-label="Slide 2"></button>
        @endif
        <?php $counter++ ?>
      @endforeach  
    </div>
  
      
    <div class="carousel-inner">
      <?php $counter = 0 ?>
      @foreach($media[$post->id] as $pic)
        @if ($counter == 0)
          <div class=" carousel-item active ">
            <img src="{{ asset($pic->url)}}" class="d-block w-65 " alt="...">
          </div>
        @else
          <div class="carousel-item ">
            <img src="{{ asset($pic->url)}}"class="d-block w-65" alt="...">
          </div>
        @endif

        <?php $counter++ ?>
      @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators{{$post->id}}" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    
    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators{{$post->id}}" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

@endif