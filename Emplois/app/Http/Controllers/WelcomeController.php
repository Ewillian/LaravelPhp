<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $jobs = Job::orderBy('updated_at')->paginate(5);
        //Afficher le contenu d'une variable puis le processus
        //var_dump($jobs);
        //die();

        return view('welcome', [
            'jobs' => $jobs
        ]);
    }
}
