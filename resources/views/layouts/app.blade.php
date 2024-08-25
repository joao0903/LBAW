<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Styles -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Nunito&family=Poppins&display=swap" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
            <div id="sidebar" class="sidebar">
                <nav class="nav-options">
                    <button onclick="w3_close()" class="w3-bar-item w3-large">Close &times;</button>
                    @if (Auth::check())
                        <h3 class="sidebar-titles">User Actions:</h3>
                        <a class="nav-option" href="{{ url('/logout') }}">Logout</a>
                        <span>{{ Auth::user()->name }}</span>
                        <a class="nav-option" href="{{ route('profile.show', ['id' => Auth::user()->id]) }}">Profile</a>
                        @if (Auth::user()->isAdmin()) 
                            <a class="nav-option" href="{{ route('userManagement.showUsers') }}">User Management</a>
                            <a class="nav-option" href="/topicManagement">Topic Management</a>
                        @endif
                    @endif
                    @php
                        use App\Models\Tag;

                        $tags = Tag::all();
                    @endphp
                    <div class="tags-sidebar">
                        <h3 class="sidebar-titles">Tags:</h3>
                        @foreach($tags as $tag)
                            <a class="tags-side-bar" href="/welcome/tag/{{ $tag->id }}"> {{ $tag->title }}</a>
                        @endforeach
                    </div>
                </nav>
            </div>
            
            <header>
                @include('components.header')
                <h1 class="FEUP-Times-title"><a href="{{ url('/welcome') }}">FEUP TIMES</a></h1>
                @include('components.navbar')
            </header>
            
            <section id="content"> 
                @yield('content')
            </section>
            @include('components.footer')
        </main>
        <script src="{{ url('js/app.js') }}"></script>
    </body>
</html>