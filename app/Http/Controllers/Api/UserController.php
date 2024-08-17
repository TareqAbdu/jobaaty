<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use Input;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use ImgUploader;
use Carbon\Carbon;
use Redirect;
use Newsletter;
use App\User;
use App\Subscription;
use App\ApplicantMessage;
use App\Company;
use App\FavouriteCompany;
use App\Gender;
use App\MaritalStatus;
use App\Country;
use App\State;
use App\City;
use App\JobExperience;
use App\JobApply;
use App\CareerLevel;
use App\Industry;
use App\Alert;
use App\FunctionalArea;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Traits\CommonUserFunctions;
use App\Traits\ProfileSummaryTrait;
use App\Traits\ProfileCvsTrait;
use App\Traits\ProfileProjectsTrait;
use App\Traits\ProfileExperienceTrait;
use App\Traits\ProfileEducationTrait;
use App\Traits\ProfileSkillTrait;
use App\Traits\ProfileLanguageTrait;
use App\Traits\Skills;
use App\Http\Requests\Front\UserFrontFormRequest;
use App\Helpers\DataArrayHelper;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function viewPublicProfile($id)
    {
        $user = User::findOrFail($id);
        $profileCv = $user->getDefaultCv();
        
        return response()->json([
            'user' => $user,
            'profileCv' => $profileCv
        ], 200);
    }

    public function myProfile()
    {
        $genders = DataArrayHelper::langGendersArray();
        $maritalStatuses = DataArrayHelper::langMaritalStatusesArray();
        $nationalities = DataArrayHelper::langNationalitiesArray();
        $countries = DataArrayHelper::langCountriesArray();
        $jobExperiences = DataArrayHelper::langJobExperiencesArray();
        $careerLevels = DataArrayHelper::langCareerLevelsArray();
        $industries = DataArrayHelper::langIndustriesArray();
        $functionalAreas = DataArrayHelper::langFunctionalAreasArray();

        $upload_max_filesize = UploadedFile::getMaxFilesize() / (1048576);
        $user = User::findOrFail(Auth::id());
        
        return response()->json([
            'genders' => $genders,
            'maritalStatuses' => $maritalStatuses,
            'nationalities' => $nationalities,
            'countries' => $countries,
            'jobExperiences' => $jobExperiences,
            'careerLevels' => $careerLevels,
            'industries' => $industries,
            'functionalAreas' => $functionalAreas,
            'user' => $user,
            'upload_max_filesize' => $upload_max_filesize
        ], 200);
    }

    public function updateMyProfile(UserFrontFormRequest $request)
    {
        $user = User::findOrFail(Auth::id());

        if ($request->hasFile('image')) {
            $this->deleteUserImage($user->id);
            $image = $request->file('image');
            $fileName = ImgUploader::UploadImage('user_images', $image, $request->input('name'), 300, 300, false);
            $user->image = $fileName;
        }
        
        if ($request->hasFile('cover_image')) {
            $this->deleteUserCoverImage($user->id);
            $cover_image = $request->file('cover_image');
            $fileName_cover_image = ImgUploader::UploadImage('user_images', $cover_image, $request->input('name'), 1140, 250, false);
            $user->cover_image = $fileName_cover_image;
        }

        $user->first_name = $request->input('first_name');
        $user->middle_name = $request->input('middle_name');
        $user->last_name = $request->input('last_name');
        $user->name = $user->getName();
        $user->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->father_name = $request->input('father_name');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->gender_id = $request->input('gender_id');
        $user->marital_status_id = $request->input('marital_status_id');
        $user->nationality_id = $request->input('nationality_id');
        $user->national_id_card_number = $request->input('national_id_card_number');
        $user->country_id = $request->input('country_id');
        $user->state_id = $request->input('state_id');
        $user->city_id = $request->input('city_id');
        $user->phone = $request->input('phone');
        $user->mobile_num = $request->input('mobile_num');
        $user->job_experience_id = $request->input('job_experience_id');
        $user->career_level_id = $request->input('career_level_id');
        $user->industry_id = $request->input('industry_id');
        $user->functional_area_id = $request->input('functional_area_id');
        $user->current_salary = $request->input('current_salary');
        $user->expected_salary = $request->input('expected_salary');
        $user->salary_currency = $request->input('salary_currency');
        $user->video_link = $request->video_link;
        $user->street_address = $request->input('street_address');
        $user->is_subscribed = $request->input('is_subscribed', 0);

        $user->update();

        $this->updateUserFullTextSearch($user);
        Subscription::where('email', 'like', $user->email)->delete();
        if((bool)$user->is_subscribed)
        {            
            $subscription = new Subscription();
            $subscription->email = $user->email;
            $subscription->name = $user->name;
            $subscription->save();
        }
        
        return response()->json(['message' => 'Profile updated successfully'], 200);
    }

    public function addToFavouriteCompany(Request $request, $company_slug)
    {
        $data['company_slug'] = $company_slug;
        $data['user_id'] = Auth::id();
        FavouriteCompany::create($data);
        
        return response()->json(['message' => 'Company has been added to favorites list'], 200);
    }

    public function removeFromFavouriteCompany(Request $request, $company_slug)
    {
        $user_id = Auth::id();
        FavouriteCompany::where('company_slug', 'like', $company_slug)->where('user_id', $user_id)->delete();

        return response()->json(['message' => 'Company has been removed from favorites list'], 200);
    }

    public function myFollowings()
    {
        $user = User::findOrFail(Auth::id());
        $companiesSlugArray = $user->getFollowingCompaniesSlugArray();
        $companies = Company::whereIn('slug', $companiesSlugArray)->get();

        return response()->json([
            'user' => $user,
            'companies' => $companies
        ], 200);
    }

    public function myMessages()
    {
        $user = User::findOrFail(Auth::id());
        $messages = ApplicantMessage::where('user_id', '=', $user->id)
                ->orderBy('is_read', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

        return response()->json([
            'user' => $user,
            'messages' => $messages
        ], 200);
    }

    public function applicantMessageDetail($message_id)
    {
        $user = User::findOrFail(Auth::id());
        $message = ApplicantMessage::findOrFail($message_id);
        $message->update(['is_read' => 1]);

        return response()->json([
            'user' => $user,
            'message' => $message
        ], 200);
    }

    public function myAlerts()
    {
        $alerts = Alert::where('email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['alerts' => $alerts], 200);
    }

    public function delete_alert($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->delete();
        
        return response()->json(['message' => 'Alert has been successfully deleted.'], 200);
    }

    public function ResumeFetch($id)
    {
        $user = User::findOrFail($id);
        $profileCv = $user->getDefaultCv();
        
        return response()->json([
            'user' => $user,
            'profileCv' => $profileCv
        ], 200);
    }
}
