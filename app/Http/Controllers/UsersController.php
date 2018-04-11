<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'index', 'create', 'store'],
        ]);
        $this->middleware('guest', [
            'only' => ['create'],
        ]);
    }
    /**
     * 用户列表页面
     * @return html
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

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
        return redirect()->route('users.show', [$user]);
    }

    /**
     * 个人资料修改页面
     * @param  User   $user 用户实例
     * @return html
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * 个人资料提交更改
     * @param  Request $request 请求实例
     * @return html
     */
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|max:50',
            'password' => 'nullable:confirmed|min:6',
        ]);

        $this->authorize('update', $user);

        $data         = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', [$user]);
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }


}
