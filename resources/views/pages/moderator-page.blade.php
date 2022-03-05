@extends('layouts.app')

@section('content')

<div class="container-sm pt-1" id="search-window">


        <div class="row mb-5">
            <div class="col">
                @if ($mode == 'users')
                    <a href=" {{route('banned-users')}} "  autofocus type="button" id="search-profiles-btn" class="btn btn-primary search-window-buttons border-dark font-weight-bold" style="color: gold;">
                        Banned
                    </a>
                    <a href=" {{route('reports')}} "  type="button" id="search-profiles-btn" class="btn btn-primary search-window-buttons border-dark font-weight-bold" >
                        Reports
                    </a>
                @else
                    <a href=" {{route('banned-users')}} " type="button" id="search-question-btn" class="btn btn-primary search-window-buttons border-dark font-weight-bold">
                        Banned
                    </a>
                    <a href=" {{route('reports')}} " autofocus type="button" id="search-profiles-btn" class="btn btn-primary search-window-buttons border-dark font-weight-bold" style="color: gold;">
                        Reports
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <div id="search-window-results" class="p-4 border border-dark">
                @if ($mode == 'users')
                        @each('partials.user', $users, 'user')
                @elseif ($mode == 'reports')
                        @each('partials.reports.reported-post-card', $posts, 'post')

                        @include('partials.reports.show-reports-modal')
                @endif
            </div>
        </div>


    </div>


@endsection