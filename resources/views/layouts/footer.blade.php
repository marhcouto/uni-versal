<nav class="navbar navbar-expand-lg navbar-light border-top border-dark fixed-bottom">
      <div class="container">

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navmenu"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navmenu">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a href="{{ route('home') }}" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('about') }}" class="nav-link bottom-navbar-links">About</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('contacts') }}"class="nav-link bottom-navbar-links">Contacts</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>