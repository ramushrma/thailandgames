<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\CustomerService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Helper\jilli;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
class UserController extends Controller
{
   private function generateUniqueCode()
    {
        do {
            $code = Str::upper(Str::random(2)) . rand(0, 9) . Str::upper(Str::random(2)) . rand(0, 9);
        } while (User::where('referral_code', $code)->exists());
        return $code;
    }
    //  public function register(Request $request){
    //       $validator = Validator::make($request->all(), [
    //          'mobile' => 'required|unique:users,mobile',
    //          'email' => 'required|email|unique:users,email',
    //          'password' => 'required|regex:/^\d{6,}$/',
    //          'confirm_password' => 'required|same:password',
    //     ]);
    
    //     $validator->stopOnFirstFailure();
    //     if($validator->fails()){
    //          $response = [
    //                         'status' => 400,
    //                         'message' => $validator->errors()->first()
    //                       ]; 
    //         return response()->json($response, 200);
    //     }
    //          $hashedPassword = $request->password;
         
    //      do{
    //       $u_id = rand(1000, 9999);
    //       $exists = DB::table('users')->where('u_id', $u_id)->exists();
    //     } while ($exists);
        
    //      $inserted = DB::table('users')->insert([
    //          'mobile' => $request->mobile,
    //          'email' => $request->email,
    //          'password' => $hashedPassword,
    //          'u_id' => $u_id,
    //          'referral_code' => $request->invite_code,
    //      ]);
     
    //      if ($inserted) {
    //          $response = [
    //              'message' => 'Registered successfully ',
    //              'status' => 200
                 
    //          ];
    //      } else {
    //          $response = [
    //              'message' => 'Failed to insert record',
    //              'status' => 400,
    //              'data' => []
    //          ];
    //      }
    //      return response()->json($response);
    //  }

    //  public function register(Request $request){
    //       $validator = Validator::make($request->all(), [
    //          'mobile' => 'required|unique:users,mobile',
    //          'email' => 'required|email|unique:users,email',
    //          'password' => 'required|regex:/^\d{6,}$/',
    //          'confirm_password' => 'required|same:password',
    //     ]);
    
    //     $validator->stopOnFirstFailure();
    //     if($validator->fails()){
    //          $response = [
    //                         'status' => 400,
    //                         'message' => $validator->errors()->first()
    //                       ]; 
    //         return response()->json($response, 200);
    //     }
    //          $hashedPassword = $request->password;
         
    //      do{
    //       $u_id = rand(1000, 9999);
    //       $exists = DB::table('users')->where('u_id', $u_id)->exists();
    //     } while ($exists);
        
    //      $inserted = DB::table('users')->insert([
    //          'mobile' => $request->mobile,
    //          'email' => $request->email,
    //          'password' => $hashedPassword,
    //          'u_id' => $u_id,
    //          'referral_code' => $request->invite_code,
    //      ]);
     
    //      if ($inserted) {
    //          $response = [
    //              'message' => 'Registered successfully ',
    //              'status' => 200
                 
    //          ];
    //      } else {
    //          $response = [
    //              'message' => 'Failed to insert record',
    //              'status' => 400,
    //              'data' => []
    //          ];
    //      }
    //      return response()->json($response);
    //  }
    
        public function register(Request $request){
           // dd($request->all());
        $referrerId = null; 
        $referrer_bonus = 0; 
        $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile',
            'password' => 'required|regex:/^\d{6,}$/',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first()
            ], 200);
        }
        $unique_code = $this->generateUniqueCode();
        if ($request->has('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            if ($referrer) {
                $referrerId = $referrer->id;
               
            }
        }
       if($referrerId){
           $referrer_bonus = DB::table('app_noti_ref_bobus')->where('id', 1)->value('bonus');
           $refrelcopen = DB::table('users')->where('id', $referrerId)->increment('bonus', $referrer_bonus);
           $history = DB::table('wallet_histories')->insert([
                "user_id" => $referrerId,
                "amount" => $referrer_bonus,
                "type_id" => 8,
                "description" => "Referral Bonus",
                "created_at" =>$currentDate,
            ]);
       }else{
          $referrerId = null; 
       }
       // dd($referrerId);
        $randomName = 'User_' . strtoupper(Str::random(5));
        $email = $request->email;
        $mobile = $request->mobile;
        $baseUrl = URL::to('/');
        $uid = $this->generateSecureRandomString(6);
        $data = [
            'name' => $randomName,
            'u_id' => $uid,
            'mobile' => $mobile,
            'password' => $request->password,
            'image' => $baseUrl . "/image/download.png",
            'status' => 1,
            'referral_code' => $unique_code,
            'referrer_id' => $referrerId,
            'bonus' => $referrer_bonus,
            'wallet' => 0.00,
            'email' => $email
        ];
        if ($request->has('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            if ($referrer) {
                $data['referrer_id'] = $referrer->id;
            }
        }
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
        try{
        // Make API request Jilli
        $response = Http::withHeaders($headers)->post($apiUrl, $payload);
        $apiResponse = json_decode($response->body());
        // Log the full response
        Log::info('Jilli API Response:', ['response' => $response->body()]);
        if($response->successful() && isset($apiResponse->accountNo)) {
            $data['accountNo'] = $apiResponse->accountNo;
            // Create user
            //dd($data);
            $user = User::create($data);
            $userId = $user->id;
            if($referrerId){
              $history = DB::table('wallet_histories')->insert([
                "user_id" => $userId,
                "amount" => $referrer_bonus,
                "type_id" => 8,
                "description" => "Referral Bonus",
                "created_at" => $currentDate,
            ]);  
            }
           
            if ($user){
                return response()->json([
                    'status' => 200,
                    'user_id' => $userId,
                    'message' => 'Registration successful',
                    'data' => [
                        'userId' => $user->id,
                        'token' => $user->createToken('UserApp')->plainTextToken
                    ],
                    'api_response' => $apiResponse
                ], 200);
            }
        }
            return response()->json([
                'status' => 400,
                'message' => 'Failed to register.',
                'api_response' => $response->body()
            ], 400);
        }catch(\Exception $e) {
        Log::error('API Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 400,
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 400);
     }
 }
   public function login(Request $request) {
    $validator = Validator::make($request->all(), [
        'password' => 'required'
    ])->stopOnFirstFailure();
    
    if ($validator->fails()) {
        return response()->json([
            'status' => 400,
            'message' => $validator->errors()->first()
        ], 200);
    }
    
    if (!$request->has('email') && !$request->has('mobile')) {
        return response()->json([
            'status' => 400,
            'message' => 'Either email or mobile is required'
        ], 200);
    }
    
    if ($request->has('mobile')) {
        $findMobile = DB::table('users')->where('mobile', $request->mobile)->first();
        
        if (!$findMobile) {
            return response()->json([
                'status' => 400,
                'message' => 'Mobile is not registered'
            ], 200);
        }
    }
     if ($request->has('email')) {
        $findemail = DB::table('users')->where('email', $request->email)->first();
        
        if (!$findemail) {
            return response()->json([
                'status' => 400,
                'message' => 'Email is not registered'
            ], 200);
        }
    }
    $user = DB::table('users')
        ->where(function ($query) use ($request) {
            if ($request->has('email')) {
                $query->where('email', $request->email);
            }
            if ($request->has('mobile')) {
                $query->orWhere('mobile', $request->mobile);
            }
        })
        ->first();
    
       if ($user && $user->password === $request->password) {
        if ($user->status == 1) {
            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'user_id' => $user->id
            ], 200);
        } else {
            return response()->json([
                'status' => 403, // Forbidden Status Code
                'message' => 'Your account is disabled. Contact admin.'
            ], 200);
        }
    } else {
        return response()->json([
            'status' => 400,
            'message' => 'Invalid credentials'
        ], 200);
    }

}

   public function profile($id) {
    $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
    $withdrawalcharges = DB::table('withdrawal_charges')->where('id', 1)->value('charges');
    // Fetch user details
    $user = DB::table('users')->where('id', $id)->first();

    // Check if user exists
    if (!$user) {
        return response()->json([
            'status' => 400,
            'message' => 'User not found'
        ], 200);
    }

    // Convert wallet and bonus to numeric
    $walletAmount = (float) $user->wallet;
    $bonusAmount = (float) $user->bonus;
    $updatedWallet = $walletAmount + $bonusAmount;
    $user->wallet = (string) $updatedWallet; // Keeping it as string to match original format
    $thirdPartyWallet = (float) $user->third_party_wallet;
    $user->totalBalance = $updatedWallet + $thirdPartyWallet;
    $user = (array) $user;
    return response()->json([
        'status' => 200,
        'message' => 'Profile retrieved successfully',
        'aviator_link' => "https://aviatorudaan.com/",
        'aviator_event_name' => "xgameaviator",
        'apk_link'=>"https://admin.xgamblur.com/xgamblur.apk",
        'charges' => $withdrawalcharges,
        'data' => $user
    ], 200);
}

     public function updateprofile(Request $request){
          if($request->name){
               $name = $request->name;
               $updated = DB::table('users')->where('id', $request->id)->update([
               'name' => $request->name,
          ]);
          }
          if($request->image_id){
              $image_id = $request->image_id;
              $fetchimage = DB::table('all_images')->where('id', $image_id)->select('image')->first();
              $findimage = $updateData['image'] = $fetchimage->image;
              $updated = DB::table('users')->where('id', $request->id)->update([
              'image' => $findimage,
          ]);
          }
          if($request->name && $request->image_id){
              $updated = DB::table('users')->where('id', $request->id)->update([
              'name' => $request->name,
              'image' => $findimage,
              'updated_at' => now()
          ]);
          }
          if($updated){
          return response()->json([
                'status' => 200,
                'message' => "Updated successfully"
            ], 200);
        }else{
            return response()->json([
                'status' => 400,
                'message' => 'No changes made or user not found'
            ], 200);
        }
    }
     public function allimage(){
           $data = DB::table('all_images')->select('id', 'image')->get();
            return response()->json([
            'status' => 200,
            'message' => 'Images retrieved successfully',
            'data' => $data
        ], 200);
        
    }
     public function editpassword(Request $request){
           $validator = Validator::make($request->all(),[
             'login_password' => 'required|regex:/^\d{6,}$/',
             'new_password' =>  'required|regex:/^\d{6,}$/',
             'confirm_password' => 'required|same:new_password',
            ]);
            $validator->stopOnFirstFailure();
            if($validator->fails()){
                 $response = [
                                'status' => 400,
                                'message' => $validator->errors()->first()
                              ]; 
                return response()->json($response, 200);
            }
            $data = DB::table('users')->where('id', $request->id)->first();
            $oldpassword = $data->password ;
            $loginpassword = $request->login_password;
            if($oldpassword === $loginpassword){
               $updated = DB::table('users')->where('id', $request->id )->update([
                'password' => $request->new_password
                ]); 
                if($updated){
                   return response()->json([
                        'status' => 200,
                        'message' => "Updated password successfully"
                    ], 200); 
                }
            }else{
                 return response()->json([
                        'status' => 400,
                        'message' => 'Please enter currect password'
                    ], 200);
            }
  }
     public function notification(){
              $notification = DB::table('notifications')->get();
             if($notification){
                return response()->json([
                  'status' => 200,
                  'data' => $notification
                ]);  
             }else{
                 return response()->json([
                  'status' => 400,
                  'data' => []
                ]); 
             }
         
    }
     public function about_us($type){
            $data = DB::table('settings')->where('id', $type)->first();
            if($data){
                return response()->json([
                    'status' => 200,
                    'data' => $data
                    ]);
            }else{
                return response()->json([
                    'status' => 200,
                    'data' => []
                    ]); 
            }
    }
     public function giftcards(Request $request){
            $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
            $validator = Validator::make($request->all(),[
                'code' => 'required',
            ])->stopOnFirstFailure();
            if($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()->first()
                ], 200);
            } 
           $user_id = $request->id;
           $code = $request->code;
           $fetch = DB::table('gift_cards')->where('code', $code)->first();
           if($fetch){
                $amount = $fetch->amount;
                $number_people = $fetch->number_people;
                $availed_num = $fetch->availed_num;
           }else{
              return response()->json([
                       'status' => 200,
                       'message' => 'Code not valid for gift'
                   ], 200); 
           }
          $check_claims = DB::table('gift_claims')->where('gift_code', $code)->where('userid', $user_id)->first();
          if($check_claims){
             return response()->json([
                       'status' => 400,
                       'message' => 'Already claimed'
                   ], 200);  
            }
           if(($number_people == $availed_num)){
               return response()->json([
                    'status' => 400,
                    'message' => 'Finished for now, try again next time!'
                ], 200);
           }else{
               $luck = DB::table('users')->where('id', $user_id)->increment('third_party_wallet', $amount);
               $luck = DB::table('gift_cards')->where('code', $code)->increment('availed_num', 1);
               $wallet_histories = DB::table('wallet_histories')->insert([
                   "user_id" =>$user_id,
                   "amount" => $amount,
                   "type_id" => 5,
                   "description" => "Gift Claim",
                   "created_at" => $currentDate
                   ]);
               $history = DB::table('gift_claims')->insert([
                    'userid' => $user_id,  
                    'gift_code' => $code, 
                    'amount' => $amount, 
                    'status' => 1, 
                    'created_at' =>$currentDate, 
                    ]);
                    if($history){
                      return response()->json([
                       'status' => 200,
                       'message' => 'Gift successfully claimed'
                   ], 200);  
                }
           }
        }
     public function Gifthistory($userid){
             $priview = DB::table('gift_claims')->select('amount','gift_code','created_at')->where('userid', $userid)->get();
             if($priview->isNotEmpty()){
                 return response()->json([
                     'status' => 200,
                     'data' => $priview
                     ],200);
             }else{
                 return response()->json([
                     'status' =>  400,
                     'data' =>  []
                     ],200);
             }
    }
        public function customer_service(){
        // Using the CustomerService model to fetch the data
        $customerService =CustomerService::where('id', 1)
            ->where('status', 1)
            ->select('name', 'Image', 'link')
            ->get();
    
        if ($customerService->isNotEmpty()) {
            $response = [
                'message' => 'Successfully',
                'status' => 200,
                'data' => $customerService
            ];
            
            return response()->json($response);
        } else {
            return response()->json([
                'message' => 'No record found',
                'status' => 400,
                'data' => []
            ], 400);
        }
    }
     public function feedback(Request $request){
           $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
           $validator = Validator::make($request->all(),[
             'feedback' => 'required',
            ]);
            $validator->stopOnFirstFailure();
            if($validator->fails()){
                 $response = [
                                'status' => 400,
                                'message' => $validator->errors()->first()
                              ]; 
                return response()->json($response, 200);
            }
          $feedback = DB::table('feedback')->insert([
              'description' => $request->feedback,
              'userid' => $request->userid,
              'status' => 1,
              'created_at' => $currentDate
              ]);
            if($feedback){
                return response()->json([
                    'status' => 200,
                    'message' => "Feedback submitted successfully"
                    ],200);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => "Failed to submit feedback"
                    ],200);
            }
        }
     public function wallettransfer($userid){
           $select = DB::table('users')->where('id', $userid)->first();
           $current_third_party_wallet = $select->third_party_wallet;
           if($current_third_party_wallet == 0){
              return response()->json([
                    'status' => 400,
                    'message' => "Insufficient balance"
                    ],200); 
           }
           $decrement = DB::table('users')->where('id', $userid)->decrement('third_party_wallet', $current_third_party_wallet);
           $increment = DB::table('users')->where('id', $userid)->increment('wallet', $current_third_party_wallet);
           if($increment){
              return response()->json([
                    'status' => 200,
                    'message' => "Transfer successfully"
                    ],200); 
           }else{
               return response()->json([
                    'status' => 400,
                    'message' => "Transfer Failed"
                    ],200); 
           }
       }
     public function bankname(){
           $data = DB::table('banks_name')->get();
           if($data){
               return response()->json([
                   "status" => 200,
                   "data" => $data
                   ],200);
           }else{
               return response()->json([
                   "status" => 400,
                   "data" => "No bank found"
                   ],200);
           }
       }
     public function addbank(Request $request){
            $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
            $validator = Validator::make($request->all(),[
                'bankname'    => 'required|string|max:255',
                'holdername'  => 'required|string|max:255',
                'accountnum'  => 'required|numeric|digits_between:5,20',
                'phone'       => 'required|numeric|digits:10',
                'mail'        => 'required|email|max:255',
                'ifsc'        => 'required',
            ])->stopOnFirstFailure();
            if($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()->first()
                ], 200);
            }
            $check = DB::table('bank_details')->where('userid', $request->userid)->first();
            if(!$check){
                $result = DB::table('bank_details')->insert([
                    "userid"      => $request->userid,
                    "bank_name"   => $request->bankname,
                    "name"        => $request->holdername,
                    "account_num" => $request->accountnum,
                    "phone_no" => $request->phone,
                    "email" => $request->mail,
                    "ifsc_code"   => $request->ifsc,
                    "created_at"  => $currentDate
                ]);
                if($result){
                 return response()->json([
                    'status' => 200,
                    'message' => "Bank add successfully"
                 ], 200);   
                }
            }else{
                $update = DB::table('bank_details')->where('userid', $request->userid)->update([
                    "userid"      => $request->userid,
                    "bank_name"   => $request->bankname,
                    "name"        => $request->holdername,
                    "account_num" => $request->accountnum,
                    "phone_no" => $request->phone,
                    "email" => $request->mail,
                    "ifsc_code"   => $request->ifsc,
                    "created_at"  => $currentDate 
               ]);
               if($update){
                 return response()->json([
                    'status' => 200,
                    'message' => "Bank update successfully"
                 ], 200);   
               }
            }
        
       }
     public function viewbank($userid){
           $fetch = DB::table('bank_details')->where('userid', $userid)->first();
           if($fetch){
               return response()->json([
                   "status" => 200,
                   "data" => $fetch
                   ],200);
           }else{
               return response()->json([
                   "status" => 400,
                   "data" => []
                   ],200); 
           }
       }

     public function getPaymentLimits(){
           $data = DB::table('payment_limits')->get();

           $datass=[];
           foreach($data as $find){
              $datass[$find->name] = $find->amount;
           }
            if($data){
               return response()->json([
                   "status"  => 200,
                   "data"  => $datass
                   ],200);
           }else{
              return response()->json([
                   "status"  => 200,
                   "data"  => []
                   ],400);
           }
       }

     public function withdrawal(Request $request){
            do{
            $orderNumber = time() . rand(1000000, 9999999);
            $exists = DB::table('withdraws')->where('order_id', $orderNumber)->exists();
            } while ($exists);
            $currentDate = Carbon::now('Asia/Kolkata');
            $usdt = $request->usdt_amount;
            $validator = Validator::make($request->all(),[
                'amount' => 'required|numeric',
                'type'   => 'required|numeric',
            ])->stopOnFirstFailure();
            if($validator->fails()){
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()->first()
                ], 200);
            }
            
           // dd($request->all());
          $charges = DB::table('withdrawal_charges')->where('id', 1)->value('charges');
          $payable_amt = $request->amount - ($request->amount * $charges / 100);
          $payable_usdt = $usdt - ($usdt * $charges / 100);
         // dd($payable_amt , $payable_usdt);
            
            $check = DB::table('user_usdt_address')->where('userid', $request->userid)->first();
           if($check){
               $acount_id = $check->id;
               $fetch = DB::table('users')->where('id', $request->userid)->first();
               $avableamount = $fetch->wallet;
               $avable_win_amount = $fetch->winning_wallet;
               $mobile = $fetch->mobile;
               $avableamount = $fetch->wallet;
               if($request->amount <= $avableamount && $request->amount <= $avable_win_amount){
                if($request->type == 1){
                     $decrement = DB::table('users')->where('id', $request->userid)->decrement('wallet', $request->amount);
                    if($decrement){
                   //  dd($request->userid,$acount_id,$request->amount,$request->amount,$mobile,$request->type,$currentDate);
                     $result = DB::table('withdraws')->insert([
                         "user_id"      => $request->userid,
                         "account_id"  => $acount_id,
                         "amount"	  => $request->amount,
                         "final_payable_amt" => $payable_amt,
                         "final_payable_usdt" => $payable_usdt,
                         "usdt_amount" => $usdt,
                         "mobile"  => $mobile,
                         "type"  =>  $request->type,
                         "order_id"  =>  $orderNumber,
                         "status"  => 1,
                         "created_at"  => $currentDate
                         ]);
                         
                          $history = DB::table('wallet_histories')->insert([
                            "user_id" => $request->userid,
                            "amount" => $request->amount,
                            "type_id" => 3,
                            "description" => "Withdraw",
                            "created_at" =>$currentDate,
                        ]);
                        
                     return response()->json([
                        'status' => 200,
                        'message' => "Withdrawal successfully"
                        ], 200);     
                 }
                }else{
                      $decrement = DB::table('users')->where('id', $request->userid)->decrement('wallet', $request->amount);
                      if($decrement){
                     $result = DB::table('withdraws')->insert([
                         "user_id"      => $request->userid,
                         "account_id"  => $acount_id,
                         "amount"	  => $request->amount,
                         "usdt_amount" => $usdt,
                         "mobile"  => $mobile,
                         "type"  =>  2,
                         "order_id"  =>  $orderNumber,
                         "status"  => 1,
                         "created_at"  => $currentDate
                         ]);
                     return response()->json([
                        'status' => 200,
                        'message' => "Withdrawal successfully"
                        ], 200);     
                 }
                }
               }else{
                     return response()->json([
                            'status' => 400,
                            'message' => "Insufficient balance"
                        ], 200); 
               }
           }else{
                     return response()->json([
                            'status' => 400,
                            'message' => "First, add your USDT details"
                        ], 200); 
           }
      } 
     public function withdrawalhistory(Request $request){
            $query = DB::table('withdraws')
                ->where('user_id', $request->userid)
                ->select('amount', 'type', 'status','usdt_amount', 'created_at','reason', 'order_id');
            if ($request->status) {
                $query->where('status', $request->status);
            }
             if ($request->type) {
                $query->where('type', $request->type);
            }
            if ($request->date) {
                $query->whereDate('created_at', '=', $request->date);
            }
            $query->orderBy('created_at', 'desc');
            $result = $query->get();
            if ($result->isEmpty()) {
                return response()->json([
                    "status" => 400,
                    "data" => []
                ], 200);
            }
            return response()->json([
                "status" => 200,
                "data" => $result
            ], 200);
        }
     public function payinghistory(Request $request){
            $query = DB::table('payins')
                ->where('user_id', $request->userid)
                ->select('cash', 'usdt_amount', 'order_id', 'status', 'created_at');
            if ($request->status){
                $query->where('status', $request->status);
            }
            if ($request->date) {
                $query->whereDate('created_at', '=', $request->date);
            }
            $query->orderBy('created_at', 'desc');
            $result = $query->get();
            if ($result->isEmpty()) {
                return response()->json([
                    "status" => 400,
                    "data" => []
                ], 200);
            }
            return response()->json([
                "status" => 200,
                "data" => $result
            ], 200);
        }
     public function TransactionType(){
         $type = DB::table('types')->select('id', 'name')->where('status', 1)->get();
          if($type){
              return response()->json([
                  "status" => 200,
                  "data" => $type
                  ],200);
          }else{
             return response()->json([
                 "status" => 400,
                 "status" => []
                 ],200);  
          }
      }
     public function walletHistories(Request $request){
        $query = DB::table('wallet_histories')
                ->where('user_id', $request->userid)
                ->select('amount','description','created_at');
            if ($request->type_id){
                $query->where('type_id', $request->type_id);
            }    
            if ($request->date) {
                $query->whereDate('created_at', '=', $request->date);
            }
            $query->orderBy('created_at', 'desc');
            $result = $query->get();
            if ($result->isEmpty()){
                return response()->json([
                    "status" => 400,
                    "data" => []
                ], 200);
            }
            return response()->json([
                "status" => 200,
                "data" => $result
            ], 200);
     }
   
     
     private function generateSecureRandomString($length = 8){
	//$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // Only uppercase letters
        $characters = '0123456789'; // You can expand this to include more characters if needed.
        $randomString = '';
    // Loop to generate the random string
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
    public function notifications(){
    $data = DB::table('app_noti_ref_bobus')->get();

    if ($data->isNotEmpty()) {
        return response()->json([
            "status" => 200,
            "data" => $data
        ], 200);
    }

    return response()->json([
        "status" => 400,
        "message" => "No notifications found"
    ], 200);
}
    
    public function banner(){
        $data = DB::table('sliders')->get();
        if($data){
        return response()->json([
            "status" => 200,
            "data" => $data,
            ],200);
        }else{
            return response()->json([
            "status" => 200,
            "data" => [],
            ],200);
        }
    }
        
}



