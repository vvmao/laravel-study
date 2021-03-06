<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Mail;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'index', 'create', 'store', 'confirmEmail'],
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
        return view('users.index', compact('users'));
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
        $statuses = $user->statuses()->orderBy('created_at','desc')->paginate(30);
        return view('users.show', compact('user','statuses'));
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

        // Auth::login($user);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        // return redirect()->route('users . show', [$user]);
        return redirect('/');
    }

    /**
     * 个人资料修改页面
     * @param User $user 用户实例
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * 个人资料提交更改
     * @param User $user
     * @param Request $request 请求实例
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
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

    protected function sendEmailConfirmationTo($user)
    {
        $view    = 'emails.confirm';
        $data    = compact('user');
        $from    = '1046562419@qq.com';
        $name    = '雪之殇';
        $to      = $user->email;
        $subject = '感谢注册sample应用！请确认你的邮箱。';
        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated        = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('session', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);

    }

}
