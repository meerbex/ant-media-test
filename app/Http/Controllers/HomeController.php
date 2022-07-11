<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stream;

class HomeController extends Controller
{
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        
        $streams = Stream::latest()->paginate(50);
    
        return view('home',compact('streams'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
