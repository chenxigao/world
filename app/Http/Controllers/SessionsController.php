<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function create(){
        return view('sessions.create');
    }


    public function store(Request $request){

        $credentials = $this->validate($request,[
        'email'=>'required|email|max:255',
            'password'=>'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))){
            if(Auth::user()->activated){

            //登录成功后的相关操作
            session()->flash('success','欢迎回来');
            return redirect()->intended(route('users.show',[Auth::user()]));
        } else {
                Auth::logout();
                session()->flash('warning','您的账号未激活，请检查邮箱中的注册邮件进行激活');
                return redirect('/');
            }
        } else {
            //登录失败后的操作
            session()->flash('danger','很抱歉，您的邮箱和密码不符');
            return redirect()->back();

        }
    }


    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '退出成功');
        return redirect('login');
    }

    public function __construct(){

        $this->middleware('guest',[
            'only'=>['create']
        ]);

    }
}
