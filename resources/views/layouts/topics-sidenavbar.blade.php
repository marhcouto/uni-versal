@if (Auth::check())
<div id='pc1' class="sidenav" style="z-index: 3;">
    
        <div class="title">
            <span id ="topics-title" class="title_name">Topics</span>
        </div>
        
        <nav class = "topics" class='animated bounceInDown bg-dark'>
            
            @php
                include $_SERVER['DOCUMENT_ROOT'] . '/../resources/views/partials/topic.php';   
            @endphp
        
            <ul>    
                <li class='sub-menu'>
                    <a href="{{route('showTopicPage', ['topic' => 'Topics'])}}">All Topics</a>
                </li>
                @foreach ($responseBody as $area => $topics)
                    <li class='sub-menu'><a >{{$area}}<i class="bi bi-arrow-down-short"></i><div class='fa fa-caret-down right'></div></a>
                        <ul>
                            @foreach ($topics as $topic)
                                <li><a href="{{route('showTopicPage', ['topic' => $topic])}}">{{$topic}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </nav>

</div>

<div id = "show-btn" >
        <button id="toogle-topics-navbar"  class="btn btn-primary" onclick="pcsh1()" ></button>
</div>

<script src="{{ asset('js/topics-sidenavbar.js') }}" defer></script>
@endif