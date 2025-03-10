<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
<<<<<<< HEAD
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\{All_image,User,withdraw,Bet,Payin};
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    
    
    public function handle($request, Closure $next)
{
    return $next($request)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
}

=======
use Illuminate\Support\Str;
use App\Models\{All_image,User,withdraw,Bet,Payin};
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
//     public function user_create(Request $request)
//     {
// 		$u_id = $request->u_id;
// 		$mobile = $request->mobile;

// 		$perPage = 10;
		
// 		$value = $request->session()->has('id');
	
//         if(!empty($value))
//         {
<<<<<<< HEAD
// 			// $users = DB::select("SELECT e.*, m.name AS sname FROM users e LEFT JOIN users m ON e.referrer_id = m.id; ");
=======

// 			// $users = DB::select("SELECT e.*, m.name AS sname FROM users e LEFT JOIN users m ON e.referrer_id = m.id; ");
			
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
// 			$query = DB::table('users')
// 				->leftJoin('users as m', 'users.referrer_id', '=', 'm.id')
// 				->select('users.*', 'm.name as sname');
		
// 			// Apply filters if provided
// 			if (!empty($u_id)) {
// 				$query->where('users.u_id', 'LIKE', '%' . $u_id . '%');
// 			}
// 			if (!empty($mobile)) {
// 				$query->where('users.mobile', 'LIKE', '%' . $mobile . '%');
// 			}

// 			// Execute the query and paginate results
// 			$users = $query->paginate($perPage);
        
//         return view ('user.index', compact('users'));
//         }
//         else
//         {
//           return redirect()->route('login');  
//         }

//     }
    
//      public function user_create(Request $request)
//     {
// 		$u_id = $request->u_id;
// 		$mobile = $request->mobile;

// 		$perPage = 10;
		
// 		$value = $request->session()->has('id');
	
//         if(!empty($value))
//         {

// 			// $users = DB::select("SELECT e.*, m.username AS sname FROM users e LEFT JOIN users m ON e.referral_user_id = m.id; ");
			
// 			$query = DB::table('users')
// 				->leftJoin('users as m', 'users.referrer_id', '=', 'm.id')
// 				->select('users.*', 'm.name as sname');
		
// 			// Apply filters if provided
// 			if (!empty($u_id)) {
// 				$query->where('users.u_id', 'LIKE', '%' . $u_id . '%');
// 			}
// 			if (!empty($mobile)) {
// 				$query->where('users.mobile', 'LIKE', '%' . $mobile . '%');
// 			}

// 			// Execute the query and paginate results
// 			$users = $query->paginate($perPage);
        
//         return view ('user.index', compact('users'));
//         }
//         else
//         {
//           return redirect()->route('login');  
//         }
        
//     }

<<<<<<< HEAD
      private function generateUniqueCode()
    {
        do {
            $code = Str::upper(Str::random(2)) . rand(0, 9) . Str::upper(Str::random(2)) . rand(0, 9);
        } while (User::where('referral_code', $code)->exists());
        return $code;
    }
     
     
      public function referral_code($referrer_code){
        return view('user.newregister', ['referrer_code' => $referrer_code]);
     }
     

 public function referral_code_register(Request $request, $referrer_code){ 
    // dd($request->all(),$referrer_code);
    $referrerId = null; 
    $referrer_bonus = 0;
    $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
    // Validation
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|unique:users,email',
        'mobile' => 'required|numeric|digits:10|unique:users,mobile',
        'password' => 'required|regex:/^\d{6,}$/',
        'confirm_password' => 'required|same:password',
    ]);

     if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
    $unique_code = $this->generateUniqueCode();
   // dd($unique_code);
    // Check Referrer
    if ($referrer_code) {
        $referrer = User::where('referral_code', $referrer_code)->first();
        if ($referrer) {
            $referrerId = $referrer->id;
        }
    }

    // Referral Bonus
    if ($referrerId) {
        $referrer_bonus = DB::table('app_noti_ref_bobus')->where('id', 1)->value('bonus');
        DB::table('users')->where('id', $referrerId)->increment('bonus', $referrer_bonus);
        DB::table('wallet_histories')->insert([
            "user_id" => $referrerId,
            "amount" => $referrer_bonus,
            "type_id" => 8,
            "description" => "Referral Bonus",
            "created_at" => $currentDate,
        ]);
    }else{
         $referrerId = null;
    }

    // User Data
    $randomName = 'User_' . strtoupper(Str::random(5));
    $email = $request->email;
    $mobile = $request->mobile;
    $baseUrl = URL::to('/');
    $uid = $this->generateSecureRandomString(6);
   
    $data = [
        'name' => $randomName,
        'u_id' => $uid,
        'mobile' => $mobile,
        'password' => $request->password,  // ✅ Password simple format me store hoga
        'image' => $baseUrl . "/image/download.png",
        'status' => 1,
        'referral_code' => $unique_code,
        'referrer_id' => $referrerId,
        'bonus' => $referrer_bonus,
        'wallet' => 0.00,
        'email' => $email
    ];
 //dd($randomName,$email,$mobile,$baseUrl,$uid, $data);
    // External API setup Jilli
    $manager_key = 'FEGIScSYS3cMy';
    $apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-newjilli-game-registration';
    $authorizationtoken='1740119423505';
    $headers = [
        'Authorization' => 'Bearer ' . $manager_key,
        'Content-Type'  => 'application/json',
        'authorizationtoken' => 'Bearer '.$authorizationtoken
    ];
    $requestData = json_encode(['mobile' => $mobile]);
    $payload = ['payload' => base64_encode($requestData)];

    try {
        // Jilli API Call
        $response = Http::withHeaders($headers)->post($apiUrl, $payload);
        $apiResponse = json_decode($response->body());

        Log::info('Jilli API Response:', ['response' => $response->body()]);

        if ($response->successful() && isset($apiResponse->accountNo)) {
            $data['accountNo'] = $apiResponse->accountNo;
           // dd($data);
            $user = User::create($data);
            $userId = $user->id;
            if($referrerId){
               DB::table('wallet_histories')->insert([
                    "user_id" => $userId,
                    "amount" => $referrer_bonus,
                    "type_id" => 8,
                    "description" => "Referral Bonus",
                    "created_at" => $currentDate,
                ]); 
            }
           return back()->with('success', 'Registration successful!');
        }
      return back()->with('error', 'Registration failed. Please try again.');

    } catch (\Exception $e) {
        Log::error('API Error:', ['error' => $e->getMessage()]);
        return response()->json([
            'status' => 400,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage()
        ], 400);
    }
}




=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
public function user_create(Request $request)
{
    $u_id = $request->u_id;
    $mobile = $request->mobile;
<<<<<<< HEAD
    $perPage = 10;
        $authuser = $request->session()->get('authuser');
        $auth_role = $authuser->role_id; 
        $auth_id = $authuser->id;
        
        $query = User::query()
        ->leftJoin('users as m', 'users.referrer_id', '=', 'm.id') // Referrer Name
        ->leftJoin('users as v', 'users.vendor_id', '=', 'v.id')   // Vendor Name & ID
        ->select('users.*', 'm.name as sname', 'v.name as vendor_name', 'v.id as vendor_id')->where('users.role_id', 4);
     if ($auth_role == 2) {
            $query = User::query()
                ->leftJoin('users as m', 'users.referrer_id', '=', 'm.id') 
                ->leftJoin('users as v', 'users.vendor_id', '=', 'v.id')  
                ->whereIn('users.vendor_id', function ($q) use ($auth_id) {
                    $q->select('id')
                        ->from('users')
                        ->where('admin_id', $auth_id);
                })
                ->select('users.*', 'm.name as sname', 'v.name as vendor_name', 'v.id as vendor_id');
        }
        if ($auth_role == 3) {
            $query = User::query()
                ->leftJoin('users as m', 'users.referrer_id', '=', 'm.id') // Referrer Name
                ->leftJoin('users as v', 'users.vendor_id', '=', 'v.id')   // Vendor Name
                ->where('users.vendor_id', $auth_id)
                ->where('users.role_id', 4)
                ->select('users.*', 'm.name as sname', 'v.name as vendor_name');
        }
=======

    $perPage = 10;

    if ($request->session()->has('id')) {
        
        $query = User::query()
            ->leftJoin('users as m', 'users.referrer_id', '=', 'm.id')
            ->select('users.*', 'm.name as sname');

        // Apply filters if provided
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
        if (!empty($u_id)) {
            $query->where('users.u_id', 'LIKE', '%' . $u_id . '%');
        }
        if (!empty($mobile)) {
            $query->where('users.mobile', 'LIKE', '%' . $mobile . '%');
        }
<<<<<<< HEAD
        $users = $query->paginate($perPage);
        return view('user.index', compact('users'));
   
}


    
public function BlockUserList(Request $request)
=======

        // Execute the query and paginate results
        $users = $query->paginate($perPage);

        return view('user.index', compact('users'));
    } else {
        return redirect()->route('login');  
    }
}
    
    public function BlockUserList(Request $request)
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
{
    $u_id = $request->u_id;
    $mobile = $request->mobile;
    $perPage = 10;
<<<<<<< HEAD
    $authuser = $request->session()->get('authuser');
    $auth_role = $authuser->role_id; 
    $auth_id = $authuser->id;

    $query = User::query()
        ->leftJoin('users as m', 'users.referrer_id', '=', 'm.id')
        ->leftJoin('users as v', 'users.vendor_id', '=', 'v.id')
        ->select('users.*', 'm.name as sname', 'v.name as vendor_name', 'v.id as vendor_id')
        ->where('users.role_id', 4)
        ->where('users.status', 0); // Sirf Block Wale User

    if ($auth_role == 2) {
        $query->whereIn('users.vendor_id', function ($q) use ($auth_id) {
            $q->select('id')
                ->from('users')
                ->where('users.role_id', 4)
                ->where('admin_id', $auth_id);
        });
    }

    if ($auth_role == 3) {
        $query->where('users.vendor_id', $auth_id)->where('users.role_id', 4);
    }

    // Filter
    if (!empty($u_id)) {
        $query->where('users.u_id', 'LIKE', '%' . $u_id . '%');
    }
    if (!empty($mobile)) {
        $query->where('users.mobile', 'LIKE', '%' . $mobile . '%');
    }

    // Pagination
    $users = $query->paginate($perPage);
    
    return view('user.index', compact('users'));
=======

    // Check if session has 'id'
    if ($request->session()->has('id')) {
        
        // Create a base query using Eloquent's query builder
        $query = User::leftJoin('users as m', 'users.referrer_id', '=', 'm.id')
                     ->select('users.*', 'm.name as sname')
                     ->where('users.status', 0);

        // Apply filters if provided
        if (!empty($u_id)) {
            $query->where('users.u_id', 'LIKE', '%' . $u_id . '%');
        }
        if (!empty($mobile)) {
            $query->where('users.mobile', 'LIKE', '%' . $mobile . '%');
        }

        // Execute the query and paginate results
        $users = $query->paginate($perPage);
        
        return view('user.index', compact('users'));
    } else {
        return redirect()->route('login');
    }
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
}


public function export_users()
{
    // Fetching data from the User model
    $users = User::select([
        'id',
        'u_id',
        'name',
        'email',
        'mobile',
        'referrer_id',
        'wallet',
        'winning_wallet',
        'commission',
        'bonus',
        'turnover',
        'today_turnover',
        'password',
        'created_at',
        'status',
    ])->get();

    // Map users' data to the desired format
    $data = $users->map(function ($user) {
        return [
            'ID' => $user->id,
            'User ID' => $user->u_id,
            'User Name' => $user->name,
            'Email' => $user->email,
            'Mobile' => $user->mobile,
            'Sponser' => $user->referrer_id,
            'Wallet' => $user->wallet,
            'Winning Wallet' => $user->winning_wallet,
            'Commission' => $user->commission,
            'Bonus' => $user->bonus,
            'Turnover' => $user->turnover,
            'Today_Turnover' => $user->today_turnover,
            'Password' => $user->password,
            'Date' => $user->created_at,
            'Status' => $user->status,
        ];
    });

    // Convert data to array format
    $dataArray = $data->toArray();

    // Define CSV headers
    $header = [
        'ID',
        'User ID',
        'User Name',
        'Email',
        'Mobile',
        'Sponser',
        'Wallet',
        'Winning Wallet',
        'Commission',
        'Bonus',
        'Turnover',
        'Today_Turnover',
        'Password',
        'Date',
        'Status',
    ];

    // Set CSV headers for file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="users_data.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add header row to CSV
    fputcsv($output, $header);

    // Add data rows to CSV
    foreach ($dataArray as $row) {
        fputcsv($output, $row);
    }

    // Close output stream
    fclose($output);
    exit(); // Exit after download is complete
}

<<<<<<< HEAD
=======

>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
public function user_details(Request $request, $id)
{
    if ($request->session()->has('id')) {

<<<<<<< HEAD
       $users = Bet::where('userid', $id)->orderBy('id', 'desc')->get();
        $withdrawal = withdraw::where('user_id', $id)->orderBy('id', 'desc')->get();
        $wallet_histories = DB::table('wallet_histories')->where('user_id', $id)->orderBy('id', 'desc')->get();
        $dipositess = Payin::where('user_id', $id)->orderBy('id', 'desc')->get();
        return view('user.user_detail', compact('dipositess', 'users', 'withdrawal','wallet_histories'));
=======
        $users = Bet::where('userid', $id)->get();
        $withdrawal = withdraw::where('user_id', $id)->get();
        $dipositess = Payin::where('user_id', $id)->get();
        
        return view('user.user_detail', compact('dipositess', 'users', 'withdrawal'));
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    } else {
        return redirect()->route('login');
    }
}


	public function user_active(Request $request, $id)
{
    if($request->session()->has('id')) 
    {
        $user = User::find($id);

        if ($user) {
            $user->status = 1;
            $user->save(); 
        }
<<<<<<< HEAD
       return back();
=======
        return redirect()->route('users');
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    } 
    else 
    {
        return redirect()->route('login');
    }
}



public function user_inactive(Request $request, $id)
{
    
    if ($request->session()->has('id')) 
    {
        
        User::where('id', $id)->update(['status' => 0]);

<<<<<<< HEAD
       return back();

=======
        return redirect()->route('users');
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    } 
    else 
    {
        return redirect()->route('login');
    }
}

<<<<<<< HEAD
=======

>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
public function password_update(Request $request, $id)
{
    if ($request->session()->has('id')) {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        
        $user->password = $request->password; 
        $user->save();

<<<<<<< HEAD
          return back();
=======
        return redirect()->route('users');
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    } else {
        return redirect()->route('login');
    }
}

<<<<<<< HEAD
public function wallet_store(Request $request, $id)
{
     $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
=======

public function wallet_store(Request $request, $id)
{
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    if ($request->session()->has('id')) {
        $wallet = $request->input('wallet');

        $request->validate([
            'wallet' => 'required|numeric|min:1',  // Ensure wallet has a valid number greater than 0
        ]);

        $user = User::find($id);
<<<<<<< HEAD
       
=======

>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
        if ($user) {
            $user->increment('wallet', $wallet);
            $user->increment('deposit_amount', $wallet);
            $user->increment('total_payin', $wallet);
            $user->increment('recharge', $wallet);

<<<<<<< HEAD
             $authuser = $request->session()->get('authuser');
             $auth_role = $authuser->role_id; 
             $auth_id = $authuser->id;
             
             $description = null;
             if($auth_role ==1){
                $description = "Super Admin";
             }
             if($auth_role ==2){
                $description = " Admin";
             }
             if($auth_role ==3){
                $description = "Vendor";
             }
             
             $wallet_histories = DB::table('wallet_histories')->insert([
                   "user_id" =>$id,
                   "added_id" => $auth_id,
                   "amount" => $wallet,
                   "type_id" => 15,
                    "description_2" => $description,
                   "description" => "Added by Admin",
                   "created_at" => $currentDate
                   ]);
                   

            return back()->with('success', 'Wallet updated successfully.');
        } else {
            return back()->with('error', 'User not found.');
=======
            Payin::create([
                'user_id' => $user->id,
                'cash' => $wallet,
                'order_id' => 'via Admin',  // Assuming fixed order_id for admin
                'type' => 0,  // Assuming '2' is the type you need
                'status' => 2,  // Assuming '2' represents success status
            ]);

            return redirect()->route('users')->with('success', 'Wallet updated successfully.');
        } else {
            return redirect()->route('users')->with('error', 'User not found.');
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
        }
    } else {
        return redirect()->route('login');
    }
}


public function wallet_subtract(Request $request, $id)
{
<<<<<<< HEAD
     $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
=======
    date_default_timezone_set('Asia/Kolkata');
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    $ammount = $request->wallet;

    // Check if the request has a wallet amount
    if ($request->has('wallet')) {
        // Retrieve the user using Eloquent
        $user = User::find($id);

        // Check if user exists
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
<<<<<<< HEAD
        
=======

>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
        // Check if the wallet amount is sufficient
        if ($user->wallet < $ammount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance.');
        }

        // Subtract the amount from the wallet
        $user->wallet -= $ammount;
        $user->save();
<<<<<<< HEAD
        
         $authuser = $request->session()->get('authuser');
         $auth_role = $authuser->role_id; 
         $auth_id = $authuser->id;
         
         $description = null;
         if($auth_role ==1){
            $description = "Super Admin";
         }
         if($auth_role ==2){
            $description = " Admin";
         }
         if($auth_role ==3){
            $description = "Vendor";
         }
        
        $wallet_histories = DB::table('wallet_histories')->insert([
                   "user_id" =>$id,
                   "added_id" => $auth_id,
                   "amount" => $ammount,
                   "type_id" => 16,
                   "description_2" => $description,
                   "description" => "Deducted by Admin",
                   "created_at" => $currentDate
                   ]);
                   
       return back()->with('success', 'Amount subtracted successfully!');
=======

        return redirect()->route('users')->with('success', 'Amount subtracted successfully!');
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    }

    return redirect()->back()->with('error', 'No amount specified.');
}


	
// 		public function password_store(Request $request ,$id)
//     {
// 		date_default_timezone_set('Asia/Kolkata');
// 		$date=date('Y-m-d H:i:s');
// 		$value = $request->session()->has('id');
	
//         if(!empty($value))
//         {
//       $password=$request->password;
			
// 			$sponser_mobile =$request->sponser_mobile;
			
//      //dd($wallet);
//          $data = DB::update("UPDATE `users` SET `password` = $password  WHERE id = $id;");
			
// 			if($sponser_mobile){

//     $sponser_data = DB::table('users')->where('mobile', $sponser_mobile)->first();
    
//     if ($sponser_data) {
     
//         $sponser_id = $sponser_data->id;
  
//         DB::table('users')->where('id', $id)->update(['referrer_id' => $sponser_id]);
//     }
// }

			
<<<<<<< HEAD
//                return back();
=======
//              return redirect()->route('users');
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
// 			  }
//         else
//         {
//           return redirect()->route('login');  
//         }
//       }

public function password_store(Request $request, $id)
{
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d H:i:s');

    if ($request->session()->has('id')) {
        $password = $request->password;
        $sponser_mobile = $request->sponser_mobile;

        // Directly updating the user's password
        User::where('id', $id)->update(['password' => $password]);

        // Updating the referrer_id if sponsor's mobile is provided and exists
        if ($sponser_mobile) {
            $sponser = User::where('mobile', $sponser_mobile)->first();

            if ($sponser) {
                User::where('id', $id)->update(['referrer_id' => $sponser->id]);
            }
        }

<<<<<<< HEAD
          return back();
=======
        return redirect()->route('users');
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    } else {
        return redirect()->route('login');
    }
}
	
	
	
	
	
		public function user_mlm(Request $request,$id)
    {
			
$value = $request->session()->has('id');
	
        if(!empty($value))
        {

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://mahajong.club/admin/index.php/Mahajongapi/level_getuserbyrefid?id=$id",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: ci_session=itqv6s6aqactjb49n7ui88vf7o00ccrf'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$data= json_decode($response);

			
      
		
             return view ('user.mlm_user_view')->with('data', $data);
			
			  }
        else
        {
           return redirect()->route('login');  
        }
      }
      
      
<<<<<<< HEAD
 
=======
      
     public function registerwithref($id){
         
         $ref_id = User::where('referral_code',$id)->first();
       
         return view('user.newregister')->with('ref_id',$ref_id);
         
     }
     
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
      protected function generateRandomUID() {
					$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$digits = '0123456789';

					$uid = '';

					// Generate first 4 alphabets
					for ($i = 0; $i < 4; $i++) {
						$uid .= $alphabet[rand(0, strlen($alphabet) - 1)];
					}

					// Generate next 4 digits
					for ($i = 0; $i < 4; $i++) {
						$uid .= $digits[rand(0, strlen($digits) - 1)];
					}

					return $this->check_exist_memid($uid);
					
				}
				
				protected function check_exist_memid($uid)
                    {
                        $check = User::where('u_id', $uid)->first();
                        if ($check) {
                            return $this->generateRandomUID();
                        } else {
                            return $uid;
                        }
                    }

// 	  protected function check_exist_memid($uid){
// 					$check = DB::table('users')->where('u_id',$uid)->first();
// 					if($check){
// 						return $this->generateRandomUID(); // Call the function using $this->
// 					} else {
// 						return $uid;
// 					}
// 				}
      
//         public function register_store(Request $request,$referral_code)
//       {


//           $validatedData = $request->validate([
//             'mobile' => 'required',
//             'password' => 'required|string|min:6|confirmed', 
//             'password_confirmation' =>'required|string|min:6', 
//             'email' => 'required | unique:users,email',
// 			'otp' => 'required',
//         ]);
//           //dd($ref_id);

//       $refer = DB::table('users')->where('referral_code', $referral_code)->first();
// 	 	if ($refer !== null) {
// 			$referrer_id = $refer->id;

						
// 	$userdata =  DB::table('users')->where('mobile', $request->mobile)->where('otp', $request->otp)
//     ->update([
//         'email' => $request->email,
//         'wallet' => 20,
//         'password' => $request->password,
//         'referrer_id' =>$referrer_id,
//         'status' => 1,
//     ]);

// 	if($userdata){
			
//      DB::select("UPDATE `users` SET `yesterday_register`=yesterday_register+1 WHERE `id`=$referrer_id");
	
//      return redirect(str_replace('https://admin.', 'http://', "https://nandigame.live"));

// 	}else{
		
// 		 return redirect()->back()->with('error', 'Mobile or Otp not match, Contact to admin..!');
		
// 	}
		
		
// }
// }

public function register_store_old(Request $request, $referral_code)
{
    $validatedData = $request->validate([
        'mobile' => 'required',
        'password' => 'required|string|min:6|confirmed', 
        'password_confirmation' => 'required|string|min:6', 
        'email' => 'required|unique:users,email',
        'otp' => 'required',
    ]);

    // Retrieve referrer information
    $referrer = User::where('referral_code', $referral_code)->first();

    if ($referrer) {
        $referrer_id = $referrer->id;

        // Attempt to find and update the user
        $user = User::where('mobile', $request->mobile)
            ->where('otp', $request->otp)
            ->first();

        if ($user) {
            $user->update([
                'email' => $request->email,
                'wallet' => 20,
                'password' => $request->password, // Hashing the password
                'referrer_id' => $referrer_id,
                'status' => 1,
            ]);

            // Update referrer's registration count
            $referrer->increment('yesterday_register');

            return redirect(str_replace('https://admin.', 'http://', "https://jupitergames.app/"));
        } else {
            return redirect()->back()->with('error', 'Mobile or OTP not match, Contact to admin..!');
        }
    }

    return redirect()->back()->with('error', 'Invalid referral code.');
}

	
	public function register_store(Request $request, $referral_code)
{
    $validatedData = $request->validate([
        'mobile' => 'required|unique:users,mobile',
        'password' => 'required|string|min:6|confirmed', 
        'password_confirmation' => 'required|string|min:6', 
        'email' => 'required|unique:users,email',
<<<<<<< HEAD
       // 'otp' => 'required',
=======
        'otp' => 'required',
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    ]);
    
     $baseUrl = URL::to('/');

    // Retrieve referrer information
    $referrer = User::where('referral_code', $referral_code)->first();
    $randomName = 'User_' . strtoupper(Str::random(5));
     $randomReferralCode = 'ZUP' . strtoupper(Str::random(4));
    if ($referrer) {
        $referrer_id = $referrer->id;

        // Attempt to find and update the user
       
           DB::table('users')->insert([
    'email' => $request->email,
	'name'=>$randomName,
	'u_id' => $this->generateSecureRandomString(8),
    'mobile' => $request->mobile,
    'wallet' => 28,
     'referral_code' => $randomReferralCode,
    'password' => $request->password,  // Hash the password
    'image' => $baseUrl . "/image/download.png",
    'referrer_id' => $referrer_id,
    'status' => 1,
]);
        
            
            // Update referrer's registration count
            $referrer->increment('yesterday_register');

            return redirect(str_replace('https://admin.', 'http://', "https://fomoplay.club/"));
       
    }

    return redirect()->back()->with('error', 'Invalid referral code.');
}

       private function generateSecureRandomString($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Only uppercase letters
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }
<<<<<<< HEAD
        return $randomString;
    }
    

     public function usdtqr(){
         $data = DB::table('manual_usdt')->first();
         
         return view ('work_order_assign.Usdtqr')->with('data', $data);
    }
 public function updateUsdtQr(Request $request) {
    $data = DB::table('manual_usdt')->where('id', $request->id)->first();
    
    if (!$data) {
        return redirect()->back()->with('error', 'Data not found.');
    }

    $updateData = [
        'usdt_wallet_address' => $request->wallet_address,
        'updated_at' => now()
    ];

    if ($request->hasFile('qr_image')) {
        $file = $request->file('qr_image');
        
        // ✅ MIME Type Manually Check
        $mimeType = $file->getClientMimeType();
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

        if (!in_array($mimeType, $allowedMimes)) {
            return redirect()->back()->with('error', 'Invalid file type.');
        }

        // ✅ File Move to `public/qr_images`
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('qr_images'), $filename);

        // ✅ Store Full URL in Database
        $updateData['qr_image'] = url('qr_images/' . $filename);
    }

    DB::table('manual_usdt')->where('id', $request->id)->update($updateData);

    return redirect()->back()->with('success', 'USDT details updated successfully.');
}
   
    public function live_scroll_notification(){
        $data = DB::table('app_noti_ref_bobus')->get();
        if ($data->isEmpty()){
            return "No data found!";
        }
        return view('gift.scroll_notification')->with('data', $data);
    }
    
     public function update(Request $request, $id){
    // Validate inpu
    $request->validate([
        'scrollnotification' => 'required|string|max:400'
    ]);

    // Find and update the notification
    $notification = DB::table('app_noti_ref_bobus')->where('id', $id)->update([
        'scrollnotification' => $request->scrollnotification,
        'bonus' => $request->bonus,
        'updated_at' => now()
    ]);
    if ($notification) {
        return redirect()->back()->with('success', ' Updated successfully!');
    } else {
        return redirect()->back()->with('error', 'Failed to update notification.');
    }
}

     public function appnotification(){
         $data = DB::table('notifications')->get();
         return view('gift.notifications')->with('data', $data);
     }
     
      public function appnotification_de($id){
         $data = DB::table('notifications')->where('id', $id)->delete();
          return redirect()->back()->with('success', ' delete successfully!');
     }
     public function appnotification_add(request $request){
         $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
         $insert = DB::table('notifications')->insert([
             'name' => $request->notification,
             'created_at' => $currentDate,
             ]);
             if($insert){
             return redirect()->back()->with('success', ' Insert successfully!');    
             }else{
               return redirect()->back()->with('error', 'Failed to Insert notification.'); 
             }
     }
     
     public function findfeedback(Request $request){
        $authuser = $request->session()->get('authuser');
        $auth_role = $authuser->role_id;
        $auth_id = $authuser->id;
        $userIds = [];

    // Case 1: Role == 2 (Admin)
    if ($auth_role == 2) {
        // Step 1: Admin ke under wale vendor id ko collect kro
        $vendor_ids = DB::table('users')
            ->where('admin_id', $auth_id)
            ->pluck('id')
            ->toArray();

        // Step 2: Vendors ke under wale users id ko collect kro
        $userIds = DB::table('users')
            ->whereIn('vendor_id', $vendor_ids)
            ->pluck('id')
            ->toArray();

        // Step 3: Vendor ki id bhi array me add kro
        $userIds = array_merge($userIds, $vendor_ids);
    }
    // Case 2: Role == 3 (Vendor)
    elseif ($auth_role == 3) {
        // Vendor ke under wale users id ko collect kro
        $userIds = DB::table('users')
            ->where('vendor_id', $auth_id)
            ->pluck('id')
            ->toArray();
    }

    // Case 3: Agar koi aur role hai to sabhi feedback return kro
    if (!empty($userIds)) {
        $data = DB::table('feedback')
            ->whereIn('userid', $userIds)
            ->orderBy('id', 'desc')
            ->get();
    } else {
        $data = DB::table('feedback')
            ->orderBy('id', 'desc')
            ->get();
    }

    return view('gift.feedback')->with('data', $data);
}
      
      public function deletefeedback($id){
        DB::table('feedback')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Feedback Deleted Successfully');
   }
	
}
=======

        return $randomString;
    }
}
      
      
	
      
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263

     
