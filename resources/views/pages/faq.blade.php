@extends('layouts.app')

@section('title', 'FEUP Times - FAQ')

@section('content')
<div class="faq-container">
    <h1>
        FAQ
        <svg class="icons" width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4 21H6V11H12V13H20V5H13V3H4V21ZM12 5H6V9H13V11H18V7H12V5Z" fill="currentColor" /></svg>
    </h1>
    <p>Here are some frequently asked questions we get from our users:</p>

    <ul class="faq-list">
        <li class="faq-item">
            <h2>How can I submit a news story?</h2>
            <p>To submit a news story, go to the "Submit News" page and fill out the form.</p>
        </li>

        <li class="faq-item">
            <h2>How can I contact the site team?</h2>
            <p>You can contact us through the "Contact" page.</p>
        </li>

        <li class="faq-item">
            <h2>How can I check the main information about the site?</h2>
            <p>To check that, go to the "About" page.</p>
        </li>

        <li class="faq-item">
            <h2>How can I edit my profile?</h2>
            <p>To edit your profile, click on the "Profile" link, select the option "Edit Profile" and change the informations that you want.</p>
        </li>

        <li class="faq-item">
            <h2>How do I search for an article?</h2>
            <p>To search for an article, click on the Search Icon on the upper right cornerand type the title of the article that you are looking.</p>
        </li>

        <li class="faq-item">
            <h2>How can I comment on an article?</h2>
            <p>To comment on an article, scroll to the bottom of the article page and enter your comment in the comment box. Click "Comment" to post your comment.</p>
        </li>

        <li class="faq-item">
            <h2>Why can't I see my comment on the article?</h2>
            <p>Comments are moderated to ensure respectful discussion. If your comment does not appear immediately, it may have been removed by an administrator.</p>
        </li>
        
        <li class="faq-item">
            <h2>How can I follow a Journalist?</h2>
            <p>To follow a Journalist, click on an article and then on author name. You'll be redirected to the profile of the person that created the post. There, an option to follow the user will be displayed.</p>
        </li>
        
        <li class="faq-item">
            <h2>How can I unfollow a journalist that I am following?</h2>
            <p>To unfollow a Journalist that you are already following, go to your profile page by clicking on the link "Profile" and there a list of the followed Journalists will be present. By clicking on the Journalist name that you want to unfollow, you'll be redirected to his profile page where an option to unfollow him will appear.</p>
        </li>
        
        <li class="faq-item">
            <h2>How can I report a problem with the website?</h2>
            <p>To report a problem with the website, please use the "Contact Us" page.</p>
        </li>

        <li class="faq-item">
            <h2>How can I delete my account?</h2>
            <p>If you wish to delete your account, please contact us through the "Contact Us" page with your request. Note that this action is irreversible.</p>
        </li>
    </ul>
</div>
@endsection