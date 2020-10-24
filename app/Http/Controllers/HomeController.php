<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Auth;
use stdClass;

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
        $users = User::all();
        $except_own_users =  new stdClass();

        // ログインしているユーザーを取り除く
        $i = 0;
        while($i < $users->count()) {
            if($users[$i]['id'] !== Auth::id()) {
                $except_own_users->user[$i] = $users[$i];
            }
            $i++;
        }

        // オブジェクトの要素の数をカウントするときは配列にキャストしてcount()を使う
        $userCount = count((array)$except_own_users);

        // ログインしているユーザーのID
        $from_user_id = Auth::id();

        return view('home', compact('except_own_users', 'userCount', 'from_user_id'));
    }
}
