<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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
            'referral_code' => null,
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
        $headers = [
            'Authorization' => 'Bearer ' . $manager_key,
            'Content-Type'  => 'application/json'
        ];
        $requestData = json_encode(['mobile' => $mobile]);
        $payload = ['payload' => base64_encode($requestData)];
        try {
        // Make API request Jilli
        $response = Http::withHeaders($headers)->post($apiUrl, $payload);
        $apiResponse = json_decode($response->body());
        // Log the full response
        Log::info('Jilli API Response:', ['response' => $response->body()]);
        if($response->successful() && isset($apiResponse->accountNo)) {
            $data['accountNo'] = $apiResponse->accountNo;
            // Create user
            $user = User::create($data);
            $userId = $user->id;
            if ($user) {
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
     
     public function login(Request $request){
            $validator = Validator::make($request->all(), [
                'email' => 'required',
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
            $user = DB::table('users')
                ->where(function ($query) use ($request) {
                    if ($request->has('email')) {
                        $query->where('email', $request->email);
                    }
                    if ($request->has('email')) {
                        $query->orWhere('mobile', $request->email);
                    }
                })
                ->first();
                
            if ($user && $user->password === $request->password) { 
                return response()->json([
                    'status' => 200,
                    'message' => 'Login successful',
                    'user_id' => $user->id
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid credentials'
                ], 200);
            }
        }
     public function profile($id){
            $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
            $user = DB::table('users')->where('id', $id)->first();
            
            $wallet = (float) ($user->wallet ?? 0); 
            $thirdPartyWallet = (float) ($user->third_party_wallet ?? 0); 
            $totalBalance = $wallet + $thirdPartyWallet;
            $wallet = number_format($wallet, 2, '.', '');
            $thirdPartyWallet = number_format($thirdPartyWallet, 2, '.', '');
            $totalBalance = number_format($totalBalance, 2, '.', '');
            
            if (!$user) {
                return response()->json([
                    'status' => 400,
                    'message' => 'User not found'
                ], 200);
            }
            $user = (array) $user;
            $user['wallet'] = number_format($wallet, 2, '.', '');
            $user['totalBalance'] = number_format($totalBalance, 2, '.', '');
            $user['currentDate'] = $currentDate; 
            return response()->json([
                'status' => 200,
                'message' => 'Profile retrieved successfully',
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
          $feedback = DB::table('feedbacks')->insert([
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
                    'status' => 200,
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
            
            $check = DB::table('bank_details')->where('userid', $request->userid)->first();
           if($check){
               $acount_id = $check->id;
               $fetch = DB::table('users')->where('id', $request->userid)->first();
               $avableamount = $fetch->wallet;
               $mobile = $fetch->mobile;
               $avableamount = $fetch->wallet;
               if($request->amount <= $avableamount){
                if($request->type == 1){
                     $decrement = DB::table('users')->where('id', $request->userid)->decrement('wallet', $request->amount);
                    if($decrement){
                   //  dd($request->userid,$acount_id,$request->amount,$request->amount,$mobile,$request->type,$currentDate);
                     $result = DB::table('withdraws')->insert([
                         "user_id"      => $request->userid,
                         "account_id"  => $acount_id,
                         "amount"	  => $request->amount,
                         "mobile"  => $mobile,
                         "type"  =>  $request->type,
                         "order_id"  =>  $orderNumber,
                         "status"  => 1,
                         "created_at"  => $currentDate
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
                            'message' => "First, add your bank details"
                        ], 200); 
           }
      } 
     public function withdrawalhistory(Request $request){
            $query = DB::table('withdraws')
                ->where('user_id', $request->userid)
                ->select('amount', 'type', 'status','usdt_amount', 'created_at', 'order_id');
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
          $type = DB::table('types')->select('id', 'name')->get();
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
            if ($request->type){
                $query->where('type_id', $request->type);
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
     
     
     
 

}




