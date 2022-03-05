<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Home
Route::get('/', 'HomeController@show')->name('home')->middleware('notbanned');

//Notifications
Route::get('/notification', 'NotificationController@getNotifications')->name('notifications')->middleware('auth','notbanned','verified');
Route::post('/notification/update', 'NotificationController@updateNotifications')->name('update-notifications')->middleware('auth','notbanned','verified');
Route::get('/notification/checkUnseen', 'NotificationController@checkNewNotifications')->name('check-notifications')->middleware('auth','notbanned','verified');
Route::post('/notification/delete', 'NotificationController@delete')->name('delete-notifications')->middleware('auth','notbanned','verified');
Route::get('/notification/{notification_id}/redirect', 'NotificationController@redirectNotification')->name('redirect-notification')->middleware('auth','notbanned','verified');




//Contacts
Route::get('/contacts', 'ContactsController@show')->name('contacts')->middleware('notbanned');
Route::post('/contacts/sendmail', 'ContactsController@sendMail')->name('contact');

//About
Route::get('/about', 'AboutController@show')->name('about')->middleware('notbanned');

//Ban
Route::post('/ban', 'BanController@ban_user')->name('ban')->middleware('auth','notbanned','verified');
Route::post('/unban', 'BanController@unban_user')->name('unban')->middleware('auth','notbanned','verified');
Route::get('/banned', 'BanController@show')->name('banned');

//Promote
Route::post('/promotionAdmin', 'PromoteController@update_to_administrator')->name('promotion-admin')->middleware('auth','notbanned','verified');
Route::post('/promotionMod', 'PromoteController@update_to_moderator')->name('promotion-mod')->middleware('auth','notbanned','verified');
Route::post('/demoteMod', 'PromoteController@demote_to_moderator')->name('demote-mod')->middleware('auth','notbanned','verified');
Route::post('/demoteUser', 'PromoteController@demote_to_user')->name('demote-user')->middleware('auth','notbanned','verified');

//Moderator/Admin Page
Route::get('/moderator/users', 'ModeratorController@showBannedUsers')->name('banned-users')->middleware('auth','notbanned','verified');;
Route::get('/moderator/reports', 'ModeratorController@showReports')->name('reports')->middleware('auth','notbanned','verified');;
Route::get('/moderator/reports/{id_post}/redirect', 'ModeratorController@redirectReport')->name('redirectReport')->middleware('auth','notbanned','verified');
Route::get('/moderator/reports/{id_post}/reports', 'ModeratorController@getPostReports')->name('getPostReposts')->middleware('auth','notbanned','verified');
Route::get('/moderator/reports/{id_post}/numReports', 'ModeratorController@getNumReports')->name('getNumReposts')->middleware('auth','notbanned','verified');


// Authentication
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login')->middleware('guest');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register')->middleware('guest');
Route::post('/register', 'Auth\RegisterController@register');
Route::get('/forgot-password','Auth\PasswordRecoveryController@showPasswordRecovery')->middleware('guest')->name('password.request');
Route::post('/forgot-password', 'Auth\PasswordRecoveryController@emailValidation')->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', 'Auth\PasswordRecoveryController@resetPasswordForm')->middleware('guest')->name('password.reset');
Route::post('/reset-password', 'Auth\PasswordRecoveryController@updatePassword')->middleware('guest')->name('password.update');



//Password Change
Route::get('password', 'ChangePasswordController@show')->name('password')->middleware('auth','notbanned','verified');
Route::post('change-password', 'ChangePasswordController@store')->name('change.password')->middleware('auth','notbanned','verified');


// Email verification
Route::get('/email/verify/{id}/{hash}','Auth\EmailVerificationController@handle')->middleware(['auth', 'signed'])->name('verification.verify');
Route::get('/email/verify', 'Auth\EmailVerificationController@getNotice')->middleware('auth')->name('verification.notice');
Route::post('/email/verification-notification', 'Auth\EmailVerificationController@resendEmail')->middleware('auth')->name('verification.send');

//Topics
Route::get('/topics', 'TopicController@showTopics')->name('topics');
Route::get('/topics/{topic}', 'TopicController@showTopicPage')->name('showTopicPage')->middleware('auth','notbanned','verified');
Route::get('/topics/{topic}/filtered', 'TopicController@showFilteredTopicPage')->name('showFilteredTopicPage')->middleware('auth','notbanned','verified');

// Posts
Route::post('/post/{id_post}/delete', 'PostController@delete')->name('deletePost')->middleware('auth','notbanned','verified');
Route::post('/post/update', 'PostController@update')->name('updatePost')->middleware('auth','notbanned','verified');
Route::post('/post/vote', 'PostController@vote')->middleware('auth','notbanned','verified');
Route::get('/post/{id_post}/rating', 'PostController@getRating')->middleware('auth','notbanned','verified');

// Drafts
Route::get('/user/drafts', 'DraftController@showDrafts')->name('showDrafts')->middleware('auth','verified', 'notbanned');
Route::get('/draft/{id}/show', 'DraftController@showDraftForm')->name('showDraftForm')->middleware('auth','verified', 'notbanned');
Route::post('/draft/update', 'DraftController@updateDraft')->name('updateDraft')->middleware('auth','verified', 'notbanned');

// Bookmarks
Route::post('/question/bookmark', 'BookmarkController@bookmark')->name('bookmarkQuestion')->middleware('auth','notbanned','verified');
Route::get('/question/{id_post}/bookmark', 'BookmarkController@getBookmark')->middleware('auth','notbanned','verified');
Route::get('/user/bookmarks', 'BookmarkController@showBookmarks')->name('showBookmarks')->middleware('auth','verified', 'notbanned');

// Questions
Route::get('/question/{id}/show', 'QuestionController@show')->name('showQuestion')->middleware('auth','notbanned','verified');
Route::get('/question/create', 'QuestionController@showCreationForm')->name('createQuestion')->middleware('auth','notbanned','verified');
Route::post('/question/create', 'QuestionController@create')->name('addQuestion')->middleware('auth','notbanned','verified');
Route::get('/question/{id}/update', 'QuestionController@updateForm')->middleware('auth','notbanned','verified');
Route::get('/question/{id_post}', 'QuestionController@find')->name('getQuestion')->middleware('auth','notbanned','verified');


// Answers
Route::post('/answer/create', 'AnswerController@create')->name('createAnswer')->middleware('auth','notbanned','verified');
Route::post('/answer/verify', 'AnswerController@verify')->name('verifyAnswer')->middleware('auth','notbanned','verified');
Route::get('/answer/{id_post}', 'AnswerController@find')->name('getAnswer')->middleware('auth','notbanned','verified');

//Reports
Route::post('/post/{id_post}/report', 'ReportController@create')->name('reportPost')->middleware('auth','notbanned','verified');
Route::get('/post/{id_post}/report/check', 'ReportController@isReported')->name('isReported')->middleware('auth','verified', 'notbanned');


// Profile
Route::get('/users/{id}/profile', 'UserController@showProfile')->name('show-profile')->middleware('auth','notbanned','verified');
Route::get('/users/{id}/profile/edit', 'UserController@showEditProfile')->name('edit-profile')->middleware('auth','notbanned','verified');
Route::post('/users/{id}/profile', 'UserController@update')->name('update-profile')->middleware('auth','notbanned','verified');
Route::post('/users/{id}/profile/delete', 'UserController@deleteAccount')->name('delete-account')->middleware('auth','notbanned','verified');



// Search
Route::get('/search', 'SearchController@search')->name('search')->middleware('auth','notbanned','verified');
Route::get('/search/{baseInput}/users/filtered', 'SearchController@showFilteredUsers')->name('filter-users')->middleware('auth','notbanned','verified');
Route::get('/search/{baseInput}/questions/filtered', 'SearchController@showFilteredQuestions')->name('filter-questions')->middleware('auth','notbanned','verified');
Route::get('/search/{baseInput}/users', 'SearchController@users')->name('search-users')->middleware('auth','notbanned','verified');
Route::get('/search/{baseInput}/questions', 'SearchController@questions')->name('search-questions')->middleware('auth','notbanned','verified');

