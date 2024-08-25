<?php
use App\Models\Topic;

$topics = Topic::where('type', 'Accept')->get();
?>

<nav class="nav-topics">
    <ul>
        @foreach ($topics as $topic)
                <li><a href="/welcome/newsbytopic/{{ $topic->id }}">{{ $topic->title }}</a></li>
        @endforeach
    </ul>
</nav>