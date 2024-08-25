@extends('layouts.app')

@section('title', 'FEUP Times - Propose Topic')  

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
<div class="propose-topic-container">
    <h1>Propose a topic to FEUP Times</h1>
    <p>Please read notice of policy compliance bellow.</p>
    <div class="propose-topic-form-container">
        <form action="{{ route('addTopic') }}" method="POST">
            @csrf
            <fieldset>
                <legend>Topic Information</legend>

                <label for="title">Topic Title:</label>
                <input name="title" id="title" type="text" placeholder="Title" required autofocus>
                @if ($errors->has('title'))
                    <span class="error">
                        {{ $errors->first('title') }}
                    </span>
                @endif
                
                <label for="description">Topic Description:</label>
                <input name="description" id="description" type="text" placeholder="Description">
                @if ($errors->has('description'))
                    <span class="error">
                        {{ $errors->first('description') }}
                    </span>
                @endif
            </fieldset>
            
            <button type="submit">Propose</button>
        </form>
    </div>
    <div class="propose-topic-text-container">
        <h3>Notice of Policy Compliance:</h3>
        <p>In accordance with our website's policies, kindly be advised that failure to adhere to the specified topic guidelines may result in the imposition of a ban. We appreciate your understanding and cooperation in maintaining a respectful and compliant online environment. Should you have any questions or concerns, please do not hesitate to reach out to our support team.</p>
        <p>Thank you for your attention to this matter.</p>
        <p>Sincerely, FEUP Times</p>
    </div>
</div>   
@endsection
