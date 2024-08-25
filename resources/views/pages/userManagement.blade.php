@extends('layouts.app')

@section('title', 'FEUP Times - User Management')  

@section('content')
<h4>
    @if (session('success'))
        <span class="success">
            {{ session('success') }}
        </span>
    @else
        <span class="success">
            {{ session('error') }}
        </span>
    @endif
</h4>
<div class="user-management-page">
    <div class="create-user">
        <h2>Create New User</h2>
        <form action="{{ route('user.create') }}" method="POST">
            @csrf
            <fieldset>
                <legend>User Information</legend>
        
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" id="firstName" placeholder="Insert First Name" required>
        
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName" id="lastName" placeholder="Insert Last Name" required>
        
                <label for="username1">Username:</label>
                <input type="text" name="username" id="username1" placeholder="Insert Username" required>
        
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Insert Email" required>
        
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Insert Password" required>
            </fieldset>
        
            <button type="submit">Create User</button>
        </form>
        
    </div>
    <div class="search-username">
        <h1 id="user-management-title">User Management</h1>
        <form class="search-user-management" action="{{ route('user.search') }}" method="GET">
            <label for="username">Search by Username:</label>
            <input type="text" name="username" id="username" value="{{ request('username') }}" placeholder="Insert username here">
            <button type="submit">Search</button>
        </form>
        <table class="user-pagination-table">
            <tr>
                <th class="table-header">ID</th>
                <th class="table-header">Name</th>
                <th class="table-header">UserName</th>
                <th class="table-header">Email</th>
                <th class="table-header">Management</th>
            </tr>
        @forelse ($users as $user)
            <tr>
                <td class="table-body">{{ $user->id }}</td>
                <td class="table-body">{{ $user->firstname . ' ' . $user->lastname }}</td>
                <td class="table-body">{{ $user->username }}</td>
                <td class="table-body">{{ $user->email }}</td>
                <td class="table-body-management">
                    <a href="#" onclick="confirmDeleteUser('{{ $user->id }}')">Delete</a>
                    <a href="{{ "/userManagement/edit/$user->id"}}">Edit</a>
                    @if ($user->isBanned())
                    <form action="{{ route('userUnban', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="" type="submit">Unban</button>
                    </form>
                @else
                    <a href="{{ "/userManagement/ban/$user->id"}}">Ban</a>
                @endif 
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No users found.</td>
            </tr>
        @endforelse
        </table>
        <span class="pagination">
            {{ $users->links() }}
        </span>
    </div>
</div>
@endsection
