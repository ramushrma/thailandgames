<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Validator;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function register_create()
    {
        return view ('admin.register');
    }
    
       public function register_store(Request $request)
    {
         $request->validate([
             'name'   => 'required | max:45',
             'email'  => 'required',
             'mobile'  =>'required',
             'user_name' =>'required',
             'password'  =>'required',
                  
         ]);
        $data=[
             'name'=>$request->name,
             'email'=>$request->email,
             'mobile'=>$request->mobile,
             'user_name'=>$request->user_name,
             'password'=>$request->password,
             'status'=>1
             ];
 
             User::create($data);
            return redirect()->route('login');
        
      }
      
    public function login()
    {
        return view('admin.login');
    }

	
	
	
    public function auth_login(Request $request) 
    {
        $request->validate([
            'email'=>'required',
            'password'=>'required',
		
            ]);
        //$login = DB::table('user')->where('email','=',$request['email'])->
       // where('password','=', $request['password'])->first();
		$login = DB::table('users')->where('email','=',$request['email'])->
        where('password','=', $request['password'])->where('role_id','=','1')->where('id','=','1')->first();
		// $otp=DB::table('otp_sms')->where('mobile','=','9167027770')->where('otp','=', $request['otp'])->first();
	
        if($login == NULL)
        {
		
            session()->flash('msg_class','danger');
            session()->flash('msg','The provided Admin do not match our records.');
            return redirect()->route('login');
		}
			
		else{
			 $request->session()->put('id', $login->id);

            return redirect()->route('dashboard'); 
			}
			
			 
        }
   
	
	
	
	public function dashboard(Request $request) 
{
    $userId = $request->session()->get('id');

    if (!empty($userId)) {
        date_default_timezone_set("Asia/Calcutta"); 
        $date = date('Y-m-d');

        $startdate = $request->input('start_date');
        $enddate = $request->input('end_date');

       

        if (empty($startdate) && empty($enddate)) {
             $users = DB::select("SELECT
    (SELECT COUNT(id) FROM users) as totaluser,
	(select count(id) from users where users.status='1')as activeuser,
    (SELECT COUNT(id) FROM game_settings) as totalgames,
    (SELECT COUNT(id) FROM bets) as totalbet,
    (SELECT COUNT(id) FROM feedback) as totalfeedback,
   COALESCE  ( (SELECT SUM(cash) FROM payins  WHERE status='2'),0) as totaldeposit,
  COALESCE  ((SELECT SUM(amount) FROM withdraws WHERE withdraws.status = 2 AND withdraws.created_at LIKE '$date%'),0)as tamount,
  COALESCE  ((SELECT SUM(amount) FROM withdraws WHERE withdraws.status = 2 ),0)as totalwithdraw,
    COALESCE((SELECT SUM(cash) FROM payins WHERE status = '2' AND payins.created_at LIKE '$date%'), 0) as tdeposit,
   SUM(commission) as commissions,
    COALESCE( (SELECT (today_turnover) FROM users WHERE id = 2 AND users.created_at LIKE '$date'),0 )as todayturnover,
    COUNT(id) as users,
    SUM(turnover) as total_turnover
FROM users;");
			
        } else {
            $users = DB::select("
                SELECT
                    (SELECT COUNT(id) FROM users WHERE created_at BETWEEN '$startdate' AND '$enddate') as totaluser,
					(select count(id) from users where created_at BETWEEN '$startdate' and '$enddate' and users.status='1')as activeuser,
                    (SELECT COUNT(id) FROM game_settings WHERE created_at BETWEEN '$startdate' AND '$enddate') as totalgames,
                    (SELECT COUNT(id) FROM bets WHERE created_at BETWEEN '$startdate' AND '$enddate') as totalbet,
                    (SELECT COUNT(id) FROM feedback WHERE created_at BETWEEN '$startdate' AND '$enddate') as totalfeedback,
                    COALESCE((SELECT SUM(cash) FROM payins WHERE status = 2 AND DATE(created_at) BETWEEN '$startdate' AND '$enddate'), 0) as totaldeposit,
                    COALESCE((SELECT SUM(amount) FROM withdraws WHERE status = 2 AND DATE(created_at) BETWEEN '$startdate' AND '$enddate'), 0) as tamount,
                    COALESCE((SELECT SUM(amount) FROM withdraws WHERE status = 2 AND DATE(created_at) BETWEEN '$startdate' AND '$enddate'), 0) as totalwithdraw,
                    COALESCE((SELECT SUM(cash) FROM payins WHERE status = 2 AND DATE(created_at) BETWEEN '$startdate' AND '$enddate'), 0) as tdeposit,
                    SUM(commission) as commissions,
                    COALESCE((SELECT today_turnover FROM users WHERE id = 2 AND DATE(created_at) = '$date'), 0) as todayturnover,
                    COUNT(id) as users,
                    SUM(turnover) as total_turnover
                FROM users
                WHERE created_at BETWEEN '$startdate' AND '$enddate'
            ");
        }
		
		
        session()->flash('msg_class','success');
        session()->flash('msg','Login Successfully ..!');
        return view('admin.index', ['users' => $users]);
    } else {
        return redirect()->route('login');  
    }
}

	
	
	

    public function logout(Request $request): RedirectResponse
    {
        
           $request->session()->forget('id');
		 session()->flash('msg_class','success');
            session()->flash('msg','Logout Successfully ..!');
     
         return redirect()->route('login')->with('success','Logout Successfully ..!');
    }
	
	   public function password_index()
    {
        return view('change_password');
    }
	

   public function password_change(Request $request)
    {
	 
	   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'npassword' => 'required|min:6',
			
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('change_password')
                ->withErrors($validator)
                ->withInput();
        }
    
        $user = DB::table('users')->where('email', $request->input('email'))->first();
    
        if ($user) {
            if ($request->input('password') === $user->password) {
 
					DB::table('users')
						->where('email', $request->input('email'))
						->update(['password' => $request->input('npassword')]);

					return redirect()->route('dashboard')->with('success','Password successfully changed.');
				}
             else {
                 
                 return redirect()->back()->with('danger','Current password is incorrect.');
                
            }
        } else {
            return redirect()->back()->with('danger','The provided email does not match our records.');
            
        }
    
        return redirect()->route('change_password')->withInput();
    }

}
