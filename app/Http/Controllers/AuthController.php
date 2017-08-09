<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\ConfirmUser;
use Mail;
use App\UserProfile;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	
	public function create(){
		return view('auth.register');
	}
	
	public function repeat(){
		return view('auth.repeat');
	}
	
	public function login(){
		return view('auth.login');
	}
	
	public function logout(){
		\Auth::logout();
		return redirect('/login');
	}
	
    public function createSave(Request $request){
		$usersearch=User::where('email','=',$request->input('email'))->first(); //делаем выборку из базы по введенному email
		if(!empty($usersearch->email)){
			if($usersearch->status==0){
				return back()->with('message_war',"Такой email уже зарегестрирован, но не подтвержден. Проверьте почту или <a href='/register_repeat'>запросите</a> повторное подтверждение email.");
			}
		}
		$validator=Validator::make($request->all(),[
				'firstname' => 'required|string|max:255',
				'lastname' => 'required|string|max:255',
				'email' => 'required|string|email|max:255|unique:users',
				'password' => 'required|string|min:6|confirmed',
			]);
		if ($validator->fails()){
			return back()->withErrors($validator)->withInput();
		}
		$firstname=$request->firstname;
		$lastname=$request->lastname;
		$name=$firstname." ".$lastname;
		$email=$request->email;
		$password=$request->password;
		
		$user=User::create([
			'name' => $name,
			'email' => $email,
			'password' => bcrypt($password),
		]);
		if($user){
			UserProfile::create([
			'user_id' => $user->id,
			'name' => $name,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'email' => $email,
			]);
			
			$email=$user->email;
			$token=str_random(32);
			$model=new ConfirmUser;
			$model->email=$email;
			$model->token=$token;
			$model->save();
			if ($request->type==1){
				$default=\App\Role::where('name','=','affiliate')->first();
				Mail::send('email.register.register_affiliate',['token'=>$token],function($u) use ($user){
					$u->from('support@market-place.su');
					$u->to($user->email);
					$u->subject('Подтверждение регистрации Вебмастера');
				});
			}
			if ($request->type==2){
				$default=\App\Role::where('name','=','advertiser')->first();
				Mail::send('email.register.register_advertiser',['token'=>$token],function($u) use ($user){
					$u->from('support@market-place.su');
					$u->to($user->email);
					$u->subject('Подтверждение регистрации Рекламодателя');
				});
			}
			$user->attachRole($default);
			if(!empty($user->id)){
				return back()->with('message_success','Спасибо за регистрацию, на указанный Email адрес выслано письмо с подтверждением.');
				}
			else{
				return back()->with('message_war','Ошибка при регистрации, пожалуйста повторите попытку');
			}
		}
	}
	
	public function repeatPost(Request $request){
		$user=User::where('email','=',$request->input('email'))->first();
		if(!empty($user->email)){
			if($user->status==0 ){
				$user->touch();
				$confirm=ConfirmUser::where('email','=',$request->input('email'))->first();
				$confirm->touch();
				$role=\DB::table('role_user')->where('user_id', $user->id)->first();
				if ($role->role_id==1){
					Mail::send('email.register.register_affiliate',['token'=>$confirm->token],function($u) use ($user){
						$u->from('support@market-place.su');
						$u->to($user->email);
						$u->subject('Подтверждение регистрации Вебмастера');
					});
				}
				if ($role->role_id==2){
					Mail::send('email.register.register_advertiser',['token'=>$confirm->token],function($u) use ($user){
						$u->from('support@market-place.su');
						$u->to($user->email);
						$u->subject('Подтверждение регистрации Рекламодателя');
					});
				}
				return back()->with('message_success','Письмо для активации успешно выслано на указанный адрес.');
			}
			else{
				return back()->with('message_war','Такой email уже подтвержден.');
			}
		}
		else{
			return back()->with('message_war','Нет пользователя с таким email адресом.');
		}
	}
	
	public function loginPost(Request $request) {
		if (\Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password'),'status'=>'1'])){
			return redirect('/home'); 
		}
		else{
			return back()->with('message_war','Не верная комбинация логин-пароль');
		}
	}
	
	public function confirm($token){
		$model=ConfirmUser::where('token','=',$token)->firstOrFail();
		$user=User::where('email','=',$model->email)->first();
		$user->status=1;
		$user->save();
		$model->delete();
		return redirect('/login')->with('message_success','Ваш аккаунт успешно активирован.');
	}
	
}
