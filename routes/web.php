<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StaticPagesController;
use App\Http\Controllers\SaveController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TopicController;

use App\Http\Controllers\SearchController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/welcome');

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// ProfilePage
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile/{id}', 'showProfile')->name('profile.show');
    Route::get('/profile/{id}/edit', 'showEditProfile');
    Route::post('/profile/{id}/edit', 'update');
    Route::post('/follow/{id}', 'follow')->name('follow');
    Route::delete('/unfollow/{id}', 'unfollow')->name('unfollow');
    Route::get('/profile/{id}/posts', 'showUserPosts');
    Route::get('/profile/{id}/saved', 'showUserSaved');
    Route::get('profile/ban/{id}', 'userBanFormProfile')->name('userBanFormProfile');
    Route::post('/ban/{id}', 'userBanProfile')->name('userBanProfile');
    Route::delete('profile/unban/{id}', 'userUnbanProfile')->name('userUnbanProfile');
});

// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search-dropdown', 'searchDropdown');
    Route::get('/search', 'search');
    Route::get('/welcome/search', 'search');
});

Route::controller(HomePageController::class)->group(function () {
    Route::get('/welcome', 'showHomePage');
});

Route::controller(StaticPagesController::class)->group(function () {
    Route::get('/about', 'showAboutPage');
    Route::get('/FAQ', 'showFAQPage');
    Route::get('/contactUs', 'showContactUsPage')->name('contactUs');
    Route::post('/contact', 'sendEmail')->name('contact.send');
});

// Article Page
Route::controller(PostController::class)->group(function () {
    Route::get('/welcome/post/{id}', 'showPost')->name('post.show');
    Route::get('/welcome', 'toprecentPosts')->name('welcome');
    Route::get('/welcome/createPost', 'showCreatePost');
    Route::post('/welcome/createPost', 'createPost')->name('createPost');
    Route::get('/welcome/post/{id}/edit', 'showEditPost');
    Route::post('/welcome/post/{id}/edit','editPost');
    Route::get('/welcome/post/{id}/delete', 'deletePost');
    Route::post('/post/{id}/addTag', 'addTag')->name('addTag');
    Route::get('/post/{id}/deleteTag/{idTag}', 'deleteTag');
    Route::get('/welcome/recent', 'recentPosts');
    Route::get('/welcome/top', 'topPosts');
});

//Comment
Route::post('/post/{id}/addComment', [CommentController::class, 'addComment'])->name('addComment');
Route::post('/post/{id}/deleteComment', [CommentController::class, 'deleteComment'])->name('deleteComment');
Route::post('/post/{id}/editComment', [CommentController::class, 'editComment'])->name('editComment');
Route::post('/post/{id}/likeComment', [CommentController::class, 'likeComment'])->name('likeComment');



// UserManagement
Route::controller(UserController::class)->group(function () {
    Route::get('/userManagement', 'showUsers')->name('userManagement.showUsers');
    Route::get('userManagement/delete/{id}', 'deleteUser');
    Route::get('userManagement/edit/{id}', 'showUserInfo');
    Route::post('userManagement/edit/{id}', 'editUserInfo');
    Route::get('userManagement/search', 'searchUser')->name('user.search');
    Route::post('userManagement/create', 'createUser')->name('user.create');
    Route::get('userManagement/ban/{id}', 'userBanForm')->name('userBanForm');
    Route::post('userManagement/ban/{id}', 'userBan')->name('userBan');
    Route::delete('userManagement/unban/{id}', 'userUnban')->name('userUnban');
});

// Save
// Example route definition
Route::get('/save/{id_post}', [SaveController::class, 'savePost'])->name('savePost');
Route::get('/saveDelete/{id_post}', [SaveController::class, 'saveDeletePost'])->name('saveDeletePost');

// Vote
Route::controller(VoteController::class)->group(function () {
    Route::post('/post/{id}/{type}', 'vote')->name('vote');
});

//NewsByTopic
Route::controller(TopicController::class)->group(function () {
    Route::get('/welcome/newsbytopic/{id}', 'showTopicNews');
    Route::get('/proposeTopic', 'showAddTopic');
    Route::post('/addTopic', 'addTopic')->name('addTopic');
    Route::get('/topicManagement', 'showTopicManagement');
    Route::get('/topicManagement/delete/{id}', 'deleteTopic');
    Route::post('/topicManagement/acceptTopic/{id}', 'acceptTopic')->name('acceptTopic');
    Route::get('/topicManagement/search', 'searchTopic')->name('topic.search');
});

Route::controller(TagController::class)->group(function () {
    Route::get('/welcome/tag/{id}', 'showTagNews');
    Route::post('/followtag/{id}', 'followtag')->name('followtag');
    Route::delete('/unfollowtag/{id}', 'unfollowtag')->name('unfollowtag');
});

Route::get('/notifications', 'NotificationController@getNotifications');

Route::controller(NotificationController::class)->group(function () {
    Route::get('/notifications', 'getNotifications')->name('getNotifications');
    Route::post('/clear_notifications', 'markAllAsRead')->name('markAllAsRead');
});
