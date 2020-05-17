<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Auth;

class UserController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        Disabled this to make the required customization easier, no need to have seperate for admin and user
        $user = Auth::user();

        if ($user->isAdmin()) {
            return view('pages.admin.home');
        } */


        return view('pages.user.home', [
            'unread' => Conversation::where('processed', 0)->orderBy('created_at', 'desc')->get(),
        ]);
    }
}
