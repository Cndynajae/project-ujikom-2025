<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CobaController extends Controller
{
    public function index() {
        return view('index');
    }

    public function show() {
        return view('show');
    }
}
