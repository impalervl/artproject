<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\SendVerificationEmail;
use App\Mail\ConfirmEmail;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use JWTAuth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_token' => base64_encode($data['email'])
        ]);
    }

    public function register(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = $this->create($request->all());

        $email = new ConfirmEmail($user);

        //mail = $this->user->email
        Mail::to('impalervl@gmail.com')->send($email);

        return response()->json(['message'=>'mail sended, please verify your email'],200);

    }

    public function verify($token)
    {
        if($user = User::where('email_token', $token)->first()){

            $user->verified = 1;

            if ($user->save()) {

                $user_token = JWTAuth::fromUser($user);

                return response()->json(['message'=>'verification success','token'=>$user_token],200);
            }
            else{
                return response()->json(['message'=>'Error with Db'],501);
            }
        }
        else{
            return response()->json(['message'=>'your verification token is wrong'],401);
        }
    }
}
