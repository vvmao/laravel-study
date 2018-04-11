<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;


class UsersController extends Controller
{
    /**
     * 用户注册页面方法
     * @return html
     */
    public function create()
    {
        return view('users.create');
    }
    /**
     * 用户展示的方法
     * @param  User   $user 用户实例
     * @return html
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    /**
     * 用户注册的响应方法
     * @param  Request $request 请求实例
     * @return ''
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|max:50',
            'email'    => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        Auth::login($user);
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show',[$user]);
    }
}
