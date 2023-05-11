<?php

namespace App\Http\Controllers;

class LeaveController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('leave.index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToLeaves()
    {
        return redirect()->route('leave.index');
    }
}
