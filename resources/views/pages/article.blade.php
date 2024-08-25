@extends('layouts.app')

@section('title', 'FEUP Times - Article')

@section('content')
    <div class="breadcrumb-container">
        <nav>
            <a href="{{ url('/') }}">Main Page</a>
            <span>&gt;</span>
            <span>{{$post['title']}}</span>
        </nav>
    </div>
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
    <article class="article-container">
        <div class="article-actions">
            @if (Auth::check() && (($post->id_user == Auth::user()->id && $post->popularity == 0) || Auth::user()->isAdmin()))
                <div class="icons">
                    <a href="#" onclick="confirmDeletePost('{{ $post->id }}')"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z" fill="currentColor" /><path d="M9 9H11V17H9V9Z" fill="currentColor" /><path d="M13 9H15V17H13V9Z" fill="currentColor" /></svg></a>
                </div>
            @endif
            @if ((Auth::check()))
                @if ($save == NULL)
                    <div class="icons">
                        <a href="{{ route('savePost', ['id_post' => $post->id]) }}"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19 20H17.1717L12.7072 15.5354C12.3166 15.1449 11.6835 15.1449 11.2929 15.5354L6.82843 20L5 20V7C5 5.34315 6.34315 4 8 4H16C17.6569 4 19 5.34314 19 7V20ZM17 7C17 6.44772 16.5523 6 16 6H8C7.44772 6 7 6.44772 7 7V17L9.87873 14.1212C11.0503 12.9497 12.9498 12.9497 14.1214 14.1212L17 16.9999V7Z" fill="currentColor" /></svg></a>
                    </div>
                @else
                    <div class="icons">
                        <a href="{{ route('saveDeletePost', ['id_post' => $post->id]) }}"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19 20H17.1717L12.7072 15.5354C12.3166 15.1449 11.6835 15.1449 11.2929 15.5354L6.82843 20L5 20V7C5 5.34315 6.34315 4 8 4H16C17.6569 4 19 5.34314 19 7V20ZM17 7C17 6.44772 16.5523 6 16 6H8C7.44772 6 7 6.44772 7 7V17L9.87873 14.1212C11.0503 12.9497 12.9498 12.9497 14.1214 14.1212L17 16.9999V7Z" fill="brown" /></svg></a>
                    </div>
                @endif
            @endif
            @if (Auth::check() && ($post->id_user == Auth::user()->id || Auth::user()->isAdmin()))
                <div class="icons">
                    <a href="/welcome/post/{{ $post->id }}/edit">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M21.2635 2.29289C20.873 1.90237 20.2398 1.90237 19.8493 2.29289L18.9769 3.16525C17.8618 2.63254 16.4857 2.82801 15.5621 3.75165L4.95549 14.3582L10.6123 20.0151L21.2189 9.4085C22.1426 8.48486 22.338 7.1088 21.8053 5.99367L22.6777 5.12132C23.0682 4.7308 23.0682 4.09763 22.6777 3.70711L21.2635 2.29289ZM16.9955 10.8035L10.6123 17.1867L7.78392 14.3582L14.1671 7.9751L16.9955 10.8035ZM18.8138 8.98525L19.8047 7.99429C20.1953 7.60376 20.1953 6.9706 19.8047 6.58007L18.3905 5.16586C18 4.77534 17.3668 4.77534 16.9763 5.16586L15.9853 6.15683L18.8138 8.98525Z" fill="currentColor" /><path d="M2 22.9502L4.12171 15.1717L9.77817 20.8289L2 22.9502Z" fill="currentColor" /></svg>
                    </a>
                </div>
            @endif
        </div>

        <h2 class="centered-title">{{$post['title']}}</h2>

        <div class="centered-image">
            <img src="{{ asset($image->imagepath) }}" alt="Article Image">
        </div>

        <div class="article-info">
            <p class="date"><strong>Date: </strong> {{ $post['date'] }}</p>
            <p class="tag"><strong>Tags:</strong>
                @foreach ($post->tags as $tag)
                    <a href="/welcome/tag/{{$tag->id}}">
                        {{ $tag->title }} 
                        @if (Auth::check() && ($post->id_user == Auth::user()->id || Auth::user()->isAdmin()))
                            <a href="/post/{{ $post->id }}/deleteTag/{{ $tag->id }}">
                                <svg class="icons" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z" fill="currentColor" /><path d="M9 9H11V17H9V9Z" fill="currentColor" /><path d="M13 9H15V17H13V9Z" fill="currentColor" /></svg>
                            </a> 
                        @endif
                        @if (!$loop->last)
                            ,
                        @endif
                    </a>
                @endforeach 
                @if (Auth::check() && ($post->id_user == Auth::user()->id || Auth::user()->isAdmin()))
                    <div class="add-tag-container">
                        <form method="POST" action="{{ route('addTag', ['id' => $post->id]) }}">
                            @csrf
                            @method('POST')
                            <label class="label-decoration" for="tag">Select a Tag:</label>
                            <select id="tag" name="tag">
                                <option value=1 selected>Universidade</option>
                                <option value=2>Desporto Universitário</option>
                                <option value=3>Ensino Superior</option>
                                <option value=4>Estudantes Internacionais</option>
                                <option value=5>Ranking Universitário</option>
                            </select>
                            <button type="submit">ADD TAG</button>
                        </form>
                    </div>
                @endif
            </p>
            <p class="topic"><strong>Topic: </strong><a href="/welcome/newsbytopic/{{$post->topics->id}}"> {{ $post->topics->title }}</a></p>            
        </div>
        
        <hr class="separator">

        <p>{{$post['description']}}</p>
        
        <div class="article-data">
            <div class="article-vote">
                <span id="popularity"><strong>Popularity: </strong>{{ $post->popularity }}</span>
                @if (Auth::check())
                    <div class="icons-container">
                        <form action="{{ route('vote', ['id' => $post->id, 'type' => 1]) }}" method="post" class="vote-form">
                            @csrf
                            <button type="submit" class="like-icon" aria-label="Like">
                                @if ($existingVoteRating == 1)
                                    <svg class="icons" width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 13H14C14 14.1046 13.1046 15 12 15C10.8954 15 10 14.1046 10 13H8C8 15.2091 9.79086 17 12 17C14.2091 17 16 15.2091 16 13Z" fill="currentColor" /><path d="M10 10C10 10.5523 9.55228 11 9 11C8.44772 11 8 10.5523 8 10C8 9.44771 8.44772 9 9 9C9.55228 9 10 9.44771 10 10Z" fill="currentColor" /><path d="M15 11C15.5523 11 16 10.5523 16 10C16 9.44771 15.5523 9 15 9C14.4477 9 14 9.44771 14 10C14 10.5523 14.4477 11 15 11Z" fill="currentColor" /><path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 7.58172 7.58172 4 12 4C16.4183 4 20 7.58172 20 12Z" fill="#D89058" /></svg>
                                @else
                                    <svg class="icons" width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 13H14C14 14.1046 13.1046 15 12 15C10.8954 15 10 14.1046 10 13H8C8 15.2091 9.79086 17 12 17C14.2091 17 16 15.2091 16 13Z" fill="currentColor" /><path d="M10 10C10 10.5523 9.55228 11 9 11C8.44772 11 8 10.5523 8 10C8 9.44771 8.44772 9 9 9C9.55228 9 10 9.44771 10 10Z" fill="currentColor" /><path d="M15 11C15.5523 11 16 10.5523 16 10C16 9.44771 15.5523 9 15 9C14.4477 9 14 9.44771 14 10C14 10.5523 14.4477 11 15 11Z" fill="currentColor" /><path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 7.58172 7.58172 4 12 4C16.4183 4 20 7.58172 20 12Z" fill="currentColor" /></svg>
                                @endif
                            </button>
                        </form>
                        {{ $likes }}

                        <form action="{{ route('vote', ['id' => $post->id, 'type' => -1]) }}" method="post" class="vote-form">
                            @csrf
                            <button type="submit" class="dislike-icon" aria-label="Dislike">
                                @if ($existingVoteRating == -1)
                                    <svg class="icons" width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 11C9.55228 11 10 10.5523 10 10C10 9.44772 9.55228 9 9 9C8.44772 9 8 9.44772 8 10C8 10.5523 8.44772 11 9 11Z" fill="currentColor" /><path d="M14 17C14 15.8954 13.1046 15 12 15C10.8954 15 10 15.8954 10 17H8C8 14.7909 9.79086 13 12 13C14.2091 13 16 14.7909 16 17H14Z" fill="currentColor" /><path d="M16 10C16 10.5523 15.5523 11 15 11C14.4477 11 14 10.5523 14 10C14 9.44772 14.4477 9 15 9C15.5523 9 16 9.44772 16 10Z" fill="currentColor" /><path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 7.58172 7.58172 4 12 4C16.4183 4 20 7.58172 20 12Z" fill="#D89058" /></svg>
                                @else 
                                    <svg class="icons" width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 11C9.55228 11 10 10.5523 10 10C10 9.44772 9.55228 9 9 9C8.44772 9 8 9.44772 8 10C8 10.5523 8.44772 11 9 11Z" fill="currentColor" /><path d="M14 17C14 15.8954 13.1046 15 12 15C10.8954 15 10 15.8954 10 17H8C8 14.7909 9.79086 13 12 13C14.2091 13 16 14.7909 16 17H14Z" fill="currentColor" /><path d="M16 10C16 10.5523 15.5523 11 15 11C14.4477 11 14 10.5523 14 10C14 9.44772 14.4477 9 15 9C15.5523 9 16 9.44772 16 10Z" fill="currentColor" /><path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM20 12C20 16.4183 16.4183 20 12 20C7.58172 20 4 16.4183 4 12C4 7.58172 7.58172 4 12 4C16.4183 4 20 7.58172 20 12Z" fill="currentColor" /></svg>
                                @endif
                            </button>
                        </form>
                        {{ $dislikes }}
                    </div>
                @endif
            </div>
        
            <p class="authorPost"><strong>Author: </strong>
                @if ($post->id_user != NULL)
                    <a href="/profile/{{ optional($post->postAuthor)->id }}">
                        {{ optional($post->postAuthor)->firstname }} {{ optional($post->postAuthor)->lastname }}
                    </a>
                @else
                    Anonymous
                @endif
            </p>
        </div>
    </article>
    <section class="comments">
        @if (Auth::check())
            <form method="POST" action="{{ route('addComment', ['id' => $post->id]) }}">
                @csrf
                <div class="comment-input">
                    <h2><label class="label-decoration" for="comment">Comment on Post: </label></h2>
                    <div id="input-and-button">
                        <input name="comment" id="comment" type="text" placeholder="Add a comment..." required>
                        @if ($errors->has('comment'))
                            <span class="error">
                                {{ $errors->first('comment') }}
                            </span>
                        @endif
                        <button type="submit">Comment</button>
                    </div>
                </div>
            </form>
        @endif
        @if (count($comments) === 0)
        <p id="no-comments-message">There are no comments yet!</p>
        @else
        @if ($errors->has('editedComment'))
            <span class="error">
                {{ $errors->first('editedComment') }}
            </span>
        @endif
        <ol class="comments-article">
            @foreach($comments as $comment)
                <li>
                    <p id="comment-{{ $comment->id }}">{{ $comment->content }}</p>
                    <p id="comment-posted-by"><strong>Posted by: </strong>
                        @if ($comment->id_user != NULL)
                            <a href="/profile/{{ $comment->commentAuthor->id }}">{{ $comment->commentAuthor->username }}</a>
                        @else
                            Anonymous
                        @endif
                    </p>
                    
                    <p id="comment-likes"><strong>Likes: </strong>{{ $comment->likes }}</p>
                    
                        <div class="comment-actions">
                            <form class="like-comment" action="{{ route('likeComment', ['id' => $comment->id]) }}" method="post">
                                @csrf
                                <input type="hidden" name="likes" value=0>
                                <button id="like-comment-button" type="submit">
                                    @php
                                        if (Auth::check()){
                                            $liked = DB::table('votecomment')->where('id_user', Auth::id())->where('id_comment', $comment->id)->first();
                                        } else {
                                            $liked = NULL;
                                        };
                                    @endphp
                                    @if (Auth::check())
                                        @if ($liked == NULL)
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.0122 5.57169L10.9252 4.48469C8.77734 2.33681 5.29493 2.33681 3.14705 4.48469C0.999162 6.63258 0.999162 10.115 3.14705 12.2629L11.9859 21.1017L11.9877 21.0999L12.014 21.1262L20.8528 12.2874C23.0007 10.1395 23.0007 6.65711 20.8528 4.50923C18.705 2.36134 15.2226 2.36134 13.0747 4.50923L12.0122 5.57169ZM11.9877 18.2715L16.9239 13.3352L18.3747 11.9342L18.3762 11.9356L19.4386 10.8732C20.8055 9.50635 20.8055 7.29028 19.4386 5.92344C18.0718 4.55661 15.8557 4.55661 14.4889 5.92344L12.0133 8.39904L12.006 8.3918L12.005 8.39287L9.51101 5.89891C8.14417 4.53207 5.92809 4.53207 4.56126 5.89891C3.19442 7.26574 3.19442 9.48182 4.56126 10.8487L7.10068 13.3881L7.10248 13.3863L11.9877 18.2715Z" fill="currentColor" /></svg>
                                        @else
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.0122 5.57169L10.9252 4.48469C8.77734 2.33681 5.29493 2.33681 3.14705 4.48469C0.999162 6.63258 0.999162 10.115 3.14705 12.2629L11.9859 21.1017L11.9877 21.0999L12.014 21.1262L20.8528 12.2874C23.0007 10.1395 23.0007 6.65711 20.8528 4.50923C18.705 2.36134 15.2226 2.36134 13.0747 4.50923L12.0122 5.57169ZM11.9877 18.2715L16.9239 13.3352L18.3747 11.9342L18.3762 11.9356L19.4386 10.8732C20.8055 9.50635 20.8055 7.29028 19.4386 5.92344C18.0718 4.55661 15.8557 4.55661 14.4889 5.92344L12.0133 8.39904L12.006 8.3918L12.005 8.39287L9.51101 5.89891C8.14417 4.53207 5.92809 4.53207 4.56126 5.89891C3.19442 7.26574 3.19442 9.48182 4.56126 10.8487L7.10068 13.3881L7.10248 13.3863L11.9877 18.2715Z" fill="red" /></svg>
                                        @endif
                                    @endif
                                </button>
                            </form>
                        @if (Auth::check() && ($comment->id_user == Auth::user()->id || Auth::user()->isAdmin()))  
                            <button class="comment-button" type="button" onclick="openEditCommentPopUp('{{ route('editComment', ['id' => $comment->id]) }}', {{ $comment->id }})">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M21.2635 2.29289C20.873 1.90237 20.2398 1.90237 19.8493 2.29289L18.9769 3.16525C17.8618 2.63254 16.4857 2.82801 15.5621 3.75165L4.95549 14.3582L10.6123 20.0151L21.2189 9.4085C22.1426 8.48486 22.338 7.1088 21.8053 5.99367L22.6777 5.12132C23.0682 4.7308 23.0682 4.09763 22.6777 3.70711L21.2635 2.29289ZM16.9955 10.8035L10.6123 17.1867L7.78392 14.3582L14.1671 7.9751L16.9955 10.8035ZM18.8138 8.98525L19.8047 7.99429C20.1953 7.60376 20.1953 6.9706 19.8047 6.58007L18.3905 5.16586C18 4.77534 17.3668 4.77534 16.9763 5.16586L15.9853 6.15683L18.8138 8.98525Z" fill="currentColor" /><path d="M2 22.9502L4.12171 15.1717L9.77817 20.8289L2 22.9502Z" fill="currentColor" /></svg>
                            </button>
                            @if (Auth::check() && (($comment->id_user == Auth::user()->id && $comment->likes == 0) || Auth::user()->isAdmin()))
                                <form class="delete-comment" action="{{ route('deleteComment', ['id' => $comment->id]) }}" method="post" id="deleteComment" onsubmit="return confirmDeleteComment()">
                                    @csrf
                                    <button class="comment-button" type="submit"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z" fill="currentColor" /><path d="M9 9H11V17H9V9Z" fill="currentColor" /><path d="M13 9H15V17H13V9Z" fill="currentColor" /></svg></button>
                                </form>
                            @endif
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>
        
        <div id="editCommentModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditCommentPopUp()">&times;</span>
                <form id="editCommentForm" action="" method="post">
                    @csrf
                    <textarea name="editedComment" id="editedComment"></textarea>
                    <button class="comment-button" type="submit">UPDATE COMMENT</button>
                </form>
            </div>
        </div>          
        @endif
    </section>
@endsection

