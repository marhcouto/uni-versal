<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Question;

class AboutController extends Controller
{
    /**
     * Shows the home page
     *
     * @return View
     */
    public function show(){
        return view('pages.about');
    }

  }