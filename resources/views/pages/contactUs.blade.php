@extends('layouts.app')

@section('title', 'FEUP Times - Contact Us')

@section('content')
<div class="container-contact-us">
    <h1>Contact Us</h1>
    <p>If you have any questions, please feel free to contact us by filling out the form below.</p>
    
    <form action="{{ route('contact.send') }}" method="post">
        @csrf
        <fieldset>
            <legend>Contact Information</legend>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Your Name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
        </fieldset>

        <fieldset>
            <legend>Message</legend>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea>
            </div>
        </fieldset>

        <button type="submit" class="btn btn-primary">SUBMIT</button>
    </form>
</div>
@endsection
