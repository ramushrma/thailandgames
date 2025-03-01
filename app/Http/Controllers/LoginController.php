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
		$login = DB::table('users')->where('email','=',$request['email'])->where('password','=', $request['password'])->whereIn('role_id', [1, 2, 3])->first();
      //  dd($login);
        $checkstatus = $login->status;
       if($checkstatus == 0){
           return redirect()->route('login')->with('msg', 'Your account is temporarily disabled. Please contact the administrator.');
       }
		// $otp=DB::table('otp_sms')->where('mobile','=','9167027770')->where('otp','=', $request['otp'])->first();
	
        if($login == NULL)
        {
		
            session()->flash('msg_class','danger');
            session()->flash('msg','The provided Admin do not match our records.');
            return redirect()->route('login');
		}
			
		else{
			$request->session()->put('authuser', $login);
			$request->session()->put('id', $login->id);
            return redirect()->route('dashboard'); 
			}
			
			 
        }
   
	
	
	
// 	public function dashboard(Request $request){
//     $userId = $request->session()->get('id');
//     if (!empty($userId)) {
//         date_default_timezone_set("Asia/Calcutta"); 
//         $date = date('Y-m-d');
        
//         $startdate = $request->input('start_date');
//         $enddate = $request->input('end_date');
        
//         $authuser = $request->session()->get('authuser');
//         $auth_role = $authuser->role_id; 
//         $auth_uid = $authuser->id;
        
//         $permissions = DB::table('users')->where('id', $auth_uid)->value('permissions');
//         $permissions = json_decode($permissions, true);
//         //  dd($permissions);

//         if (empty($startdate) && empty($enddate)) {
//              $users = DB::select("SELECT
//                 (SELECT COUNT(id) FROM users) as totaluser,
//             	(select count(id) from users where users.status='1')as activeuser,
//                 (SELECT COUNT(id) FROM game_settings) as totalgames,
//                 (SELECT COUNT(id) FROM bets) as totalbet,
//                 (SELECT COUNT(id) FROM feedback) as totalfeedback,
//               COALESCE  ( (SELECT SUM(cash) FROM payins  WHERE status='2'),0) as totaldeposit,
//               COALESCE  ((SELECT SUM(amount) FROM withdraws WHERE withdraws.status = 2 AND withdraws.created_at LIKE '$date%'),0)as tamount,
//               COALESCE  ((SELECT SUM(amount) FROM withdraws WHERE withdraws.status = 2 ),0)as totalwithdraw,
//                 COALESCE((SELECT SUM(cash) FROM payins WHERE status = '2' AND payins.created_at LIKE '$date%'), 0) as tdeposit,
//               SUM(commission) as commissions,
//                 COALESCE( (SELECT (today_turnover) FROM users WHERE id = 2 AND users.created_at LIKE '$date'),0 )as todayturnover,
//                 COUNT(id) as users,
//                 SUM(turnover) as total_turnover
//             FROM users;");
			
//         } else {
//             $users = DB::select("
//                 SELECT
//                     (SELECT COUNT(id) FROM users WHERE created_at BETWEEN '$startdate' AND '$enddate') as totaluser,
// 					(select count(id) from users where created_at BETWEEN '$startdate' and '$enddate' and users.status='1')as activeuser,
//                     (SELECT COUNT(id) FROM game_settings WHERE created_at BETWEEN '$startdate' AND '$enddate') as totalgames,
//                     (SELECT COUNT(id) FROM bets WHERE created_at BETWEEN '$startdate' AND '$enddate') as totalbet,
//                     (SELECT COUNT(id) FROM feedback WHERE created_at BETWEEN '$startdate' AND '$enddate') as totalfeedback,
//                     COALESCE((SELECT SUM(cash) FROM payins WHERE status = 2 AND DATE(created_at) BETWEEN '$startdate' AND '$enddate'), 0) as totaldeposit,
//                     COALESCE((SELECT SUM(amount) FROM withdraws WHERE status = 2 AND DATE(created_at) BETWEEN '$startdate' AND '$enddate'), 0) as tamount,
//                     COALESCE((SELECT SUM(amount) FROM withdraws WHERE status = 2 AND DATE(created_at) BETWEEN '$startdate' AND '$enddate'), 0) as totalwithdraw,
//                     COALESCE((SELECT SUM(cash) FROM payins WHERE status = 2 AND DATE(created_at) BETWEEN '$startdate' AND '$enddate'), 0) as tdeposit,
//                     SUM(commission) as commissions,
//                     COALESCE((SELECT today_turnover FROM users WHERE id = 2 AND DATE(created_at) = '$date'), 0) as todayturnover,
//                     COUNT(id) as users,
//                     SUM(turnover) as total_turnover
//                 FROM users
//                 WHERE created_at BETWEEN '$startdate' AND '$enddate'
//             ");
//         }
		
		
//         session()->flash('msg_class','success');
//         session()->flash('msg','Login Successfully ..!');
//           return view('admin.index', [
//                 'users' => $users,
//                 'permissions' => $permissions
//             ]);
//         } else {
//             return redirect()->route('login');  
//         }
// }

public function dashboard(Request $request)
{
    $userId = $request->session()->get('id');
    if (!empty($userId)) {
        date_default_timezone_set("Asia/Calcutta");
        $date = date('Y-m-d');
        $startdate = $request->input('start_date');
        $enddate = $request->input('end_date');

        $authuser = $request->session()->get('authuser');
        $auth_role = $authuser->role_id;
        $auth_uid = $authuser->id;

        $permissions = DB::table('users')->where('id', $auth_uid)->value('permissions');
        $permissions = json_decode($permissions, true);

        $userCondition = "1=1";
        $commonCondition = "1=1";
        $dateCondition = "";

        if (!empty($startdate) && !empty($enddate)) {
            $dateCondition = "AND created_at BETWEEN '$startdate' AND '$enddate'";
        }

        $totalUserQuery = "SELECT COUNT(id) FROM users WHERE role_id = 4"; // Default for else
        $totalFeedback = "0";
        $totalDeposit = "0";
        $totalWithdraw = "0";
        $totalPlayer = 0;

        if ($auth_role == 2) {
            $userCondition = "admin_id = $auth_uid";
            $commonCondition = "admin_id = $auth_uid";

            $vendors = DB::table('users')
                ->where('admin_id', $auth_uid)
                ->where('role_id', 3)
                ->pluck('id')->toArray();

            if (!empty($vendors)) {
                $users = DB::table('users')
                    ->whereIn('vendor_id', $vendors)
                    ->where('role_id', 4)
                    ->pluck('id')->toArray();

                $totalPlayer = count($users);

                if (!empty($users)) {
                    $totalFeedback = DB::table('feedback')
                        ->whereIn('userid', $users)
                        ->when(!empty($startdate) && !empty($enddate), function ($query) use ($startdate, $enddate) {
                            return $query->whereBetween('created_at', [$startdate, $enddate]);
                        })
                        ->count();

                    $totalDeposit = DB::table('payins')
                        ->whereIn('user_id', $users)
                        ->where('status', '2')
                        ->sum('cash');

                    $totalWithdraw = DB::table('withdraws')
                        ->whereIn('user_id', $users)
                        ->where('status', '2')
                        ->sum('amount');
                }
            }
        } elseif ($auth_role == 3) {
            $userCondition = "vendor_id = $auth_uid";
            $commonCondition = "vendor_id = $auth_uid";

            $users = DB::table('users')
                ->where('vendor_id', $auth_uid)
                ->where('role_id', 4)
                ->pluck('id')->toArray();

            $totalPlayer = count($users);

            if (!empty($users)) {
                $totalFeedback = DB::table('feedback')
                    ->whereIn('userid', $users)
                    ->when(!empty($startdate) && !empty($enddate), function ($query) use ($startdate, $enddate) {
                        return $query->whereBetween('created_at', [$startdate, $enddate]);
                    })
                    ->count();

                $totalDeposit = DB::table('payins')
                    ->whereIn('user_id', $users)
                    ->where('status', '2')
                    ->sum('cash');

                $totalWithdraw = DB::table('withdraws')
                    ->whereIn('user_id', $users)
                    ->where('status', '2')
                    ->sum('amount');
            }
        } else {
            $totalDeposit = DB::table('payins')
                ->where('status', '2')
                ->sum('cash');

            $totalWithdraw = DB::table('withdraws')
                ->where('status', '2')
                ->sum('amount');

            $totalPlayer = DB::table('users')
                ->where('role_id', 4)
                ->count();
        }

        $users = DB::select("
            SELECT
                (SELECT COUNT(id) FROM users WHERE role_id = 2) as totaladmin,
                (SELECT COUNT(id) FROM users WHERE role_id = 3 AND $userCondition) as totalvendor,
                $totalPlayer as totaluser,
                (SELECT COUNT(id) FROM users WHERE status='1' AND $userCondition $dateCondition) as activeuser,
                (SELECT COUNT(id) FROM game_settings WHERE status ='1' AND 1=1 $dateCondition) as totalgames,
                (SELECT COUNT(id) FROM bets WHERE $commonCondition $dateCondition) as totalbet,
                $totalFeedback as totalfeedback,
                $totalDeposit as totaldeposit,
                $totalWithdraw as totalwithdraw,
                COALESCE((SELECT SUM(cash) FROM payins WHERE status = '2' AND $commonCondition AND created_at LIKE '$date%'), 0) as tdeposit,
                COALESCE((SELECT SUM(amount) FROM withdraws WHERE status = 2 AND $commonCondition AND created_at LIKE '$date%'), 0) as tamount,
                COALESCE((SELECT SUM(commission) FROM users WHERE $userCondition $dateCondition), 0) as commissions,
                COALESCE((SELECT today_turnover FROM users WHERE id = $auth_uid AND DATE(created_at) = '$date'), 0) as todayturnover,
                COALESCE(SUM(turnover), 0) as total_turnover
            FROM users
            WHERE $userCondition $dateCondition
            GROUP BY $userCondition");
        session()->flash('msg_class', 'success');
        session()->flash('msg', 'Dashboard Data Loaded Successfully!');
        return view('admin.index', [
            'users' => $users,
            'auth_role' => $auth_role,
            'permissions' => $permissions
        ]);
    } else {
        return redirect()->route('login');
    }
}













	
	

    public function logout(Request $request): RedirectResponse
    {
        
           $request->session()->forget('id');
           $request->session()->forget('authuser');
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
