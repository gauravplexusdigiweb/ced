<?php

namespace App\Http\Controllers\Admin\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Login\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function Login(){
        return view('Admin.Login.Login');
    }

    public function check(LoginRequest $request){
        $login = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if ($login) {
            if (Auth::user()->user_type == 'Admin') {
                return array("status"=>true,"data"=>'','message'=>'Successfully Login');
            }else{
                return array("status"=>false,"data"=>'','message'=>'Sorry, You Are Not Allowed to Access This panel');
            }
        } else {
            return array("status"=>false,"data"=>'','message'=>'Wrong Login Details! Try Again');
        }
    }

    function Logout(){
        Auth::logout();
        $page='Logout';
        return redirect(route('Admin.Login'));
    }
}
