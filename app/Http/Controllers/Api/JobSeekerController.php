<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FetchJobSeekers;
use App\Helpers\DataArrayHelper;

class JobSeekerController extends Controller
{
    use FetchJobSeekers;

    private $functionalAreas = '';
    private $countries = '';

    public function __construct()
    {
        $this->functionalAreas = DataArrayHelper::langFunctionalAreasArray();
        $this->countries = DataArrayHelper::langCountriesArray();
    }

    public function jobSeekersBySearch(Request $request)
    {
        $search = $request->query('search', '');
        $functional_area_ids = $request->query('functional_area_id', []);
        $country_ids = $request->query('country_id', []);
        $state_ids = $request->query('state_id', []);
        $city_ids = $request->query('city_id', []);
        $career_level_ids = $request->query('career_level_id', []);
        $gender_ids = $request->query('gender_id', []);
        $industry_ids = $request->query('industry_id', []);
        $job_experience_ids = $request->query('job_experience_id', []);
        $current_salary = $request->query('current_salary', '');
        $expected_salary = $request->query('expected_salary', '');
        $salary_currency = $request->query('salary_currency', '');
        $order_by = $request->query('order_by', 'id');
        $limit = $request->query('limit', 10);

        $jobSeekers = $this->fetchJobSeekers(
            $search, $industry_ids, $functional_area_ids, $country_ids, 
            $state_ids, $city_ids, $career_level_ids, $gender_ids, 
            $job_experience_ids, $current_salary, $expected_salary, 
            $salary_currency, $order_by, $limit
        );

        return response()->json([
            'jobSeekers' => $jobSeekers,
            'functionalAreas' => $this->functionalAreas,
            'countries' => $this->countries,
        ], 200);
    }

    public function getJobSeekerDetails($id)
    {
        $jobSeeker = User::findOrFail($id);
        return response()->json($jobSeeker, 200);
    }
}
