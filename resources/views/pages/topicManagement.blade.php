@extends('layouts.app')

@section('title', 'FEUP Times - Topic Management')  

@section('content')
<div class="topic-management-page">
    <h1 id="user-management-title">Topic Management</h1>
        <form class="search-user-management" action="{{ route('topic.search') }}" method="GET">
            <label for="title">Search by Topic Title:</label>
            <input type="text" name="title" id="title" value="{{ request('title') }}">
            <button type="submit">Search</button>
        </form>

        <table class="user-pagination-table">
            <tr>
                <th class="table-header">ID</th>
                <th class="table-header">Title</th>
                <th class="table-header">Description</th>
                <th class="table-header">Type</th>
                <th class="table-header">Management</th>
            </tr>
            @forelse ($topics as $topic)
            <tr>
                <td class="table-body">{{ $topic->id }}</td>
                <td class="table-body">{{ $topic->title}}</td>
                <td class="table-body">{{ $topic->description }}</td>
                <td class="table-body">{{ $topic->type }}</td>
                @if ($topic->type == 'Pending')
                    <td class="table-body-management">
                        <form action="{{ route('acceptTopic', $topic->id) }}" method="POST">
                            @csrf
                            <button id="add-topic-button" type="submit">Add</button>
                        </form>
                        <a href="{{ "/topicManagement/delete/$topic->id" }}">Delete</a>
                    </td>
                @else
                <td class="table-body">Topic in Feup Times</td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="5">No topics found.</td>
            </tr>
        @endforelse
        </table>
        <span class="pagination">
            {{ $topics->links() }}
        </span>
</div>
@endsection