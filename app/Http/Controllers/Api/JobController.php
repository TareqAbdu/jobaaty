<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use Carbon\Carbon;
use App\Job;
use App\JobApply;
use App\FavouriteJob;
use App\Company;
use App\Country;
use App\State;
use App\City;
use App\CareerLevel;
use App\FunctionalArea;
use App\JobType;
use App\JobShift;
use App\Gender;
use App\Seo;
use App\JobExperience;
use App\DegreeLevel;
use App\ProfileCv;
use App\Helpers\MiscHelper;
use App\Helpers\DataArrayHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\JobFormRequest;
use App\Http\Requests\Front\ApplyJobFormRequest;
use App\Http\Controllers\Controller;
use App\Traits\FetchJobs;
use App\Events\JobApplied;

class JobController extends Controller
{
    use FetchJobs;

    private $functionalAreas = '';
    private $countries = '';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['jobsBySearch', 'jobDetail', 'setStatus']]);
        $this->functionalAreas = DataArrayHelper::langFunctionalAreasArray();
        $this->countries = DataArrayHelper::langCountriesArray();
    }

    public function jobsBySearch(Request $request)
{
    $search = $request->query('search', '');
    $job_titles = $request->query('job_title', []);
    $company_ids = $request->query('company_id', []);
    $industry_ids = $request->query('industry_id', []);
    $job_skill_ids = $request->query('job_skill_id', []);
    $functional_area_ids = $request->query('functional_area_id', []);
    $functionalAreaName = $request->input('functional_area_name');
    $country_ids = $request->query('country_id', []);
    $state_ids = $request->query('state_id', []);
    $city_ids = $request->query('city_id', []);
    $is_freelance = $request->query('is_freelance', []);
    $career_level_ids = $request->query('career_level_id', []);
    $job_type_ids = $request->query('job_type_id', []);
    $job_shift_ids = $request->query('job_shift_id', []);
    $gender_ids = $request->query('gender_id', []);
    $degree_level_ids = $request->query('degree_level_id', []);
    $job_experience_ids = $request->query('job_experience_id', []);
    $salary_from = $request->query('salary_from', '');
    $salary_to = $request->query('salary_to', '');
    $salary_currency = $request->query('salary_currency', '');
    $is_featured = $request->query('is_featured', 2);
    $order_by = $request->query('order_by', 'id');
    $limit = 16;
    $feature_jobs = Job::where('is_featured', 1)->notExpire()->get();

    $jobs = $this->fetchJobs(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        $order_by, $limit
    );

    $jobTitlesArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.title'
    );

    $jobIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.id'
    );

    $skillIdsArray = $this->fetchSkillIdsArray($jobIdsArray);

    $countryIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.country_id'
    );

    $stateIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.state_id'
    );

    $cityIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.city_id'
    );

    $companyIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.company_id'
    );

    $industryIdsArray = $this->fetchIndustryIdsArray($companyIdsArray);

    $functionalAreaIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.functional_area_id'
    );

    $careerLevelIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.career_level_id'
    );

    $jobTypeIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.job_type_id'
    );

    $jobShiftIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.job_shift_id'
    );

    $genderIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.gender_id'
    );

    $degreeLevelIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.degree_level_id'
    );

    $jobExperienceIdsArray = $this->fetchIdsArray(
        $search, $job_titles, $company_ids, $industry_ids, $job_skill_ids, 
        $functional_area_ids, $country_ids, $state_ids, $city_ids, $is_freelance, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, $degree_level_ids, 
        $job_experience_ids, $salary_from, $salary_to, $salary_currency, $is_featured, 
        'jobs.job_experience_id'
    );

    $seoArray = $this->getSEO(
        $functional_area_ids, $country_ids, $state_ids, $city_ids, 
        $career_level_ids, $job_type_ids, $job_shift_ids, $gender_ids, 
        $degree_level_ids, $job_experience_ids
    );

    $currencies = DataArrayHelper::currenciesArray();

    $seo = Seo::where('seo.page_title', 'like', 'jobs')->first();

    return response()->json([
        'functionalAreas' => $this->functionalAreas,
        'countries' => $this->countries,
        'currencies' => array_unique($currencies),
        'jobs' => $jobs,
        'jobTitlesArray' => $jobTitlesArray,
        'skillIdsArray' => $skillIdsArray,
        'countryIdsArray' => $countryIdsArray,
        'stateIdsArray' => $stateIdsArray,
        'cityIdsArray' => $cityIdsArray,
        'companyIdsArray' => $companyIdsArray,
        'industryIdsArray' => $industryIdsArray,
        'functionalAreaIdsArray' => $functionalAreaIdsArray,
        'careerLevelIdsArray' => $careerLevelIdsArray,
        'jobTypeIdsArray' => $jobTypeIdsArray,
        'jobShiftIdsArray' => $jobShiftIdsArray,
        'genderIdsArray' => $genderIdsArray,
        'degreeLevelIdsArray' => $degreeLevelIdsArray,
        'jobExperienceIdsArray' => $jobExperienceIdsArray,
        'feature_jobs' => $feature_jobs,
        'seo' => $seo,
    ], 200);
}

    public function jobDetail(Request $request, $job_slug)
    {
        $job = Job::where('slug', $job_slug)->firstOrFail();
        $relatedJobs = $this->fetchRelatedJobs($job);
        $seo = $this->getSeoObject($job);

        return response()->json([
            'job' => $job,
            'relatedJobs' => $relatedJobs,
            'seo' => $seo,
        ], 200);
    }

    public function setStatus(Request $request)
    {
        $statuses = ['applied', 'shortlist', 'hired', 'rejected'];
        foreach ($statuses as $status) {
            if ($request->has($status)) {
                JobApply::whereIn('id', $request->input($status))->update(['status' => $status]);
            }
        }

        return response()->json(['message' => 'Status updated successfully'], 200);
    }

    public function addToFavouriteJob(Request $request, $job_slug)
    {
        FavouriteJob::create([
            'job_slug' => $job_slug,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['message' => 'Job added to favourites'], 200);
    }

    public function removeFromFavouriteJob(Request $request, $job_slug)
    {
        FavouriteJob::where('job_slug', $job_slug)->where('user_id', Auth::id())->delete();

        return response()->json(['message' => 'Job removed from favourites'], 200);
    }

    public function applyJob(Request $request, $job_slug)
    {
        $user = Auth::user();
        $job = Job::where('slug', $job_slug)->firstOrFail();

        if (!$user->is_active) {
            return response()->json(['message' => 'Account is inactive, contact admin'], 403);
        }

        if ($this->isPackageExpired($user)) {
            return response()->json(['message' => 'Please subscribe to package first'], 403);
        }

        if ($user->isAppliedOnJob($job->id)) {
            return response()->json(['message' => 'Already applied for this job'], 400);
        }

        $myCvs = ProfileCv::where('user_id', $user->id)->pluck('title', 'id')->toArray();

        return response()->json([
            'job_slug' => $job_slug,
            'job' => $job,
            'myCvs' => $myCvs,
        ], 200);
    }

    public function postApplyJob(ApplyJobFormRequest $request, $job_slug)
    {
        $user = Auth::user();
        $job = Job::where('slug', $job_slug)->firstOrFail();

        $jobApply = new JobApply();
        $jobApply->user_id = $user->id;
        $jobApply->job_id = $job->id;
        $jobApply->cv_id = $request->post('cv_id');
        $jobApply->current_salary = $request->post('current_salary');
        $jobApply->expected_salary = $request->post('expected_salary');
        $jobApply->salary_currency = $request->post('salary_currency');
        $jobApply->save();

        if ($this->isPackageActive()) {
            $user->availed_jobs_quota += 1;
            $user->update();
        }

        event(new JobApplied($job, $jobApply));

        return response()->json(['message' => 'Successfully applied for the job'], 200);
    }

    public function myJobApplications(Request $request)
    {
        $myAppliedJobIds = Auth::user()->getAppliedJobIdsArray();
        $jobs = Job::whereIn('id', $myAppliedJobIds)->paginate(10);

        return response()->json([
            'jobs' => $jobs,
        ], 200);
    }

    public function myFavouriteJobs(Request $request)
    {
        $myFavouriteJobSlugs = Auth::user()->getFavouriteJobSlugsArray();
        $jobs = Job::whereIn('slug', $myFavouriteJobSlugs)->paginate(10);

        return response()->json([
            'jobs' => $jobs,
        ], 200);
    }

    // Helper methods to fetch related jobs and SEO objects
    private function fetchRelatedJobs($job)
    {
        // implement the logic to fetch related jobs
        return [];
    }

    private function getSeoObject($job)
    {
        // implement the logic to create SEO object
        return (object)[
            'seo_title' => $job->title,
            'seo_description' => '',
            'seo_keywords' => '',
            'seo_other' => ''
        ];
    }

    private function isPackageExpired($user)
    {
        return config('jobseeker.is_jobseeker_package_active') &&
               ($user->jobs_quota <= $user->availed_jobs_quota || $user->package_end_date->lt(Carbon::now()));
    }

    private function isPackageActive()
    {
        return (bool)config('jobseeker.is_jobseeker_package_active');
    }
}
