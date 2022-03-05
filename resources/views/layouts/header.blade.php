<header class="fixed-top m-auto">
    <nav class="navbar navbar-expand-xl navbar-light fixed-top border-bottom border-dark" id="navbar-top">
      <div class="container-fluid">
        
        <a href="{{ route('home') }}" class="img-responsive2" ><img src="{{ asset('images/logoCropped.png') }}" id="logo-navbar"></a> 
        @if (Auth::check() && !Auth::user()->isBanned())
        <form method="GET" class="header-search d-flex me-auto main-search" id="search-navbar" action=" {{ route('search') }} ">
          {{ csrf_field() }}
            <input required class="form-control" type="search" placeholder="Search Question" name="search-input" aria-label="Search">
        </form>
        @endif
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
          <span class="navbar-toggler-icon"></span>
        </button>
          <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto">
              @if (Auth::check() ) 
                  <a href="{{route('createQuestion')}}" type="button" class="btn btn-primary" id="createQuestion-navbar-button">
                      <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                      </svg>
                      Create Question
                      </a>
                  <button type="button" id="show-notif-button" href="" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#NotificationModal" >
                          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-bell-fill text-align" viewBox="0 0 16 16">
                              <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                          </svg>
                          <i id="notif-alert-icon" class="bi bi-patch-exclamation"></i>
                  </button>
                  
                  <div class="btn-group">
                        <a class="btn btn-primary" id="profile-navbar-button" href=" {{ route('show-profile', ['id' => Auth::id() ])}} ">
                          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                              <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"></path>
                          </svg>
                        </a>
                      <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="profile-arrow-navbar">
              
                      </button>
                      <div class="dropdown-menu">
                          <button type="button" href="" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#BookmarkModal" >Marked Posts</button> 
                          @if(Auth::user()->isAdmin() || Auth::user()->isModerator())                        
                            <a class="dropdown-item" href="{{ route('banned-users')}}">Mod. Page</a>
                          @endif
                          <a class="dropdown-item" href="{{ route('logout') }}">Sign Out</a>
                      </div>
                  </div>
              @else
                <a id="login-button-navbar" class="btn btn-primary btn-lg" href="{{ route('login') }}" >Login</a>
                <a id="sign-up-button-navbar" class="btn btn-primary btn-lg" href=" {{ route('register') }} ">Sign Up</a>
              @endif
            </ul>
          </div>
        </div>
    </nav>
</header>

@include('partials.bookmarks.bookmark-modal')
<script type="module" src="{{ asset('js/bookmark.js') }}" defer></script>


@include('partials.notifications.notification-modal')