<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\UserKey;

class HomeController extends Controller
{

    public function index()
    {        
        //get Recipient List
        $recipientList = User::where('id', '!=', auth()->id())->get();
        // Return the dashboard view with the users list
        return view('dashboard', compact('recipientList'));
    }

}
