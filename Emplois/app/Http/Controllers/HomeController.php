<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Job;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $company = Auth::user()->companies->first();
        $jobs = Job::where('company_id', $company->id)->paginate(5);

        //var_dump($jobs);
        //die();

        return view('home', [
            'jobs' => $jobs
        ]);
    }
}
