<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\VerificationController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\JobSeekerController;
use App\Http\Controllers\Api\SeekerMessageApiController;  

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

 Route::post('register', [RegisterController::class, 'register']);
 Route::post('login', [LoginController::class, 'login']);
 Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
 Route::post('reset-password', [ResetPasswordController::class, 'reset']);
 Route::post('email/resend', [VerificationController::class, 'resend']);
 Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

 Route::middleware('auth:api')->group(function () {
  Route::get('user/profile/{id}', [UserController::class, 'viewPublicProfile']);
  Route::get('user/my-profile', [UserController::class, 'myProfile']);
  Route::post('user/update-profile', [UserController::class, 'updateMyProfile']);
  Route::post('user/favourite-company/{company_slug}', [UserController::class, 'addToFavouriteCompany']);
  Route::delete('user/favourite-company/{company_slug}', [UserController::class, 'removeFromFavouriteCompany']);
  Route::get('user/my-followings', [UserController::class, 'myFollowings']);
  Route::get('user/my-messages', [UserController::class, 'myMessages']);
  Route::get('user/message-detail/{message_id}', [UserController::class, 'applicantMessageDetail']);
  Route::get('user/my-alerts', [UserController::class, 'myAlerts']);
  Route::delete('user/alert/{id}', [UserController::class, 'delete_alert']);
  Route::get('user/resume/{id}', [UserController::class, 'ResumeFetch']);

  //Job Api 

  Route::get('jobs/search', [JobController::class, 'jobsBySearch']);
  Route::get('jobs/{job_slug}', [JobController::class, 'jobDetail']);
  Route::post('jobs/set-status', [JobController::class, 'setStatus']);
  Route::post('jobs/favourite/{job_slug}', [JobController::class, 'addToFavouriteJob']);
  Route::delete('jobs/favourite/{job_slug}', [JobController::class, 'removeFromFavouriteJob']);
  Route::post('jobs/apply/{job_slug}', [JobController::class, 'applyJob']);
  Route::post('jobs/apply/submit/{job_slug}', [JobController::class, 'postApplyJob']);
  Route::get('jobs/my-applications', [JobController::class, 'myJobApplications']);
  Route::get('jobs/my-favourites', [JobController::class, 'myFavouriteJobs']);
  Route::get('job-seekers/search', [JobSeekerController::class, 'jobSeekersBySearch']);
  Route::get('job-seeker/{id}', [JobSeekerController::class, 'getJobSeekerDetails']);

  //Message 

  Route::get('messages', [SeekerMessageApiController::class, 'all_messages']);
  Route::post('messages/append', [SeekerMessageApiController::class, 'append_messages']);
  Route::post('messages/submit', [SeekerMessageApiController::class, 'submit_message']);
  Route::post('messages/submitnew', [SeekerMessageApiController::class, 'submitnew_message']);
  Route::post('messages/appendonly', [SeekerMessageApiController::class, 'appendonly_messages']);
  Route::post('messages/change-status', [SeekerMessageApiController::class, 'change_message_status']);
});



