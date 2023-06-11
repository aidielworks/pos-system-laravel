<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Onboarding;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('onboarding.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Onboarding  $onboarding
     * @return \Illuminate\Http\Response
     */
    public function show(Onboarding $onboarding)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Onboarding  $onboarding
     * @return \Illuminate\Http\Response
     */
    public function edit(Onboarding $onboarding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Onboarding  $onboarding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Onboarding $onboarding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Onboarding  $onboarding
     * @return \Illuminate\Http\Response
     */
    public function destroy(Onboarding $onboarding)
    {
        //
    }
}
