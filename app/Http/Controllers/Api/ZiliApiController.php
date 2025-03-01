<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Slider;
use App\Models\BankDetail; // Import your model
use Carbon\Carbon;
use App\Models\Payin;
use App\Models\WalletHistory;
use App\Models\withdraw;
use App\Models\GiftCard;
use App\Models\GiftClaim;
use App\Models\CustomerService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ZiliApiController extends Controller
{
	
	public function get_reseller_info(?string $manager_key=null){
		$manager_key = $manager_key??'FEGIScSYS3cMy';
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-reseller-info';
<<<<<<< HEAD
		$authorizationtoken='1740119423505';
		//$manager_key = 'FEGIScSYS3cMy';
	    $headers = [
	        'authorization' => 'Bearer ' .$manager_key,
	        'authorizationtoken' => 'Bearer '.$authorizationtoken
	        ];
=======
		//$manager_key = 'FEGIScSYS3cMy';
	    $headers = ['authorization' => 'Bearer ' .$manager_key];
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
		
		try {
				$response = Http::withHeaders($headers)->get($apiUrl);
				$apiResponse = json_decode($response->body());
               if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					return response()->json(['status'=>200,'message'=>$apiResponse,]);
				}
				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status'=>400,'message'=>$e->getMessage()]);
			}
	}
	
	public function test_get_user_info(){
	/*	 $apiUrl = 'https://jiliapi.igtechgaming.com/jili/get-jiliuser-info?userId=llf7llbqvyea23naa8ikehjkr6';
		$response = Http::get($apiUrl);
		$apiResponse = json_decode($response->body());
		dd($apiResponse);    */
		
		
		//$token=User::where('id',$user_id)->first();
		$user_token='eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJkYXRhIjp7ImppbGxpX2FjY291bnRfaWQiOiJsNndxYm92d2w2ajN3YTE1M2x0NDJoY2E2YiJ9LCJhbGdvcml0aG0iOiJSUzI1NiIsImlhdCI6MTczNTIyMzA0M30.fGj4yHHkkzfAJcmiXxcU2qMvBTJb_VzXY6gLzA1rq5c';
		
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-user-info';
		$manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
		$authorizationtoken='1740119423505';
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$user_token,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$user_token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			];
		$payloadpar = ['payload'=>''];
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
			dd($apiResponse);
			
			$money=$apiResponse->money;
			//dd($money);
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					$update=User::where('id', $user_id)->update(['wallet' => $money]);
					return response()->json(['status'=>200,'message'=>$apiResponse->msg,'Updated_money'=>$money]);
				}
				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse->msg]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status'=>400,'message'=>$e->getMessage()]);
			}
		

	}
	
<<<<<<< HEAD
	
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
	public function user_register(Request $request){
	
         $validator = Validator::make($request->all(), [
					'mobile' => 'required|unique:users,mobile'
					//'email' => 'required|email|unique:users,email'
				]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				}     
			$mobile = $request->mobile;
			//$email = $request->email;   

			$manager_key = 'FEGIScSYS3cMy';
			$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-newjilli-game-registration';
<<<<<<< HEAD
			$authorizationtoken='1740119423505';
			    // Custom headers
			$headers = [
			    'authorization' => 'Bearer ' . $manager_key,
			    'authorizationtoken' => 'Bearer '.$authorizationtoken
			    ];
=======
			
			    // Custom headers
			$headers = ['authorization' => 'Bearer ' . $manager_key];
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263

				//   request data //
			$requestData = ['mobile' => $mobile];
			$requestData  = json_encode($requestData);
			$requestData  = base64_encode($requestData);
		    $payload = ['payload'=>$requestData];
		
			try {
				// Make API request with headers and JSON body
				$response = Http::withHeaders($headers)->post($apiUrl, $payload);

				// Log response
			   // Log::info('PayIn API Response:', ['response' => $response->body()]);
			   // Log::info('PayIn API Status Code:', ['status' => $response->status()]);
                //dd($response->body());
				// Parse API response
				$apiResponse = json_decode($response->body());
				//dd($apiResponse);
				

				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					  $account_no = $apiResponse->accountNo;
					//dd($account_token);
					  $inserted_id = DB::table('users')->insertGetId(['mobile'=>$mobile , 'accountNo'=>$account_no]);
					  return response()->json([
						  'status' => 200,
						  'message' => 'user registered successfully.',
						   'data' =>$apiResponse,'id'=>$inserted_id
					  ], 200); 
				}

				// Handle API errors
				return response()->json(['status' => 400,'message' => 'Failed to register.', 'api_response' => $response->body()], 400);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status' => 400, 'message' => 'Internal Server Error','error' => $e->getMessage()], 400);
			}
       }
	public function all_game_list(Request $request){
			
			$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-all-games-list';
			$token = 'FEGIScSYS3cMy';
<<<<<<< HEAD
			$authorizationtoken='1740119423505';
			
			$headers = [
				'authorization' => 'Bearer ' .$token,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
			
			$headers = [
				'authorization' => 'Bearer ' .$token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			];
			$payload = ['payload'=>''];
			
			try {
				// Make API request with headers and JSON body
				$response = Http::withHeaders($headers)->post($apiUrl, $payload);
				//dd($response);
				// Parse API response
				$apiResponse = json_decode($response->body());
				//dd($apiResponse);
                
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					  return response()->json([
						  'status' => 200,
						  'message' => 'Game list..',
						   'data' =>$apiResponse->data,
						  'fish' =>$apiResponse->fish,
						  'slot' =>$apiResponse->slot,
						  'tableandcard' =>$apiResponse->tableandcard,
						  'crash' =>$apiResponse->crash
					  ], 200); 
				}

				// Handle API errors
				return response()->json(['status' => 400,'message' => 'Failed to get game list.', 'api_response' => $response->body()], 400);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status' => 400, 'message' => 'Internal Server Error','error' => $e->getMessage()], 400);
			}
		}
	
		public function all_game_list_old(Request $request){
			$validator = Validator::make($request->all(), [
				'user_id' => 'required|exists:users,id'
			]);
			$validator->stopOnFirstFailure();
			if ($validator->fails()) {
				return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
			} 
			$user_id = $request->user_id;
			$account_token = DB::table('users')->where('id',$user_id)->value('accountNo');
			
			$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-all-games-list';
			$token = 'FEGIScSYS3cMy';
<<<<<<< HEAD
			$authorizationtoken='1740119423505';
			$headers = [
			    
				'authorization' => 'Bearer ' .$token,
				'validateuser' => 'Bearer '.$account_token,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
			
			$headers = [
				'authorization' => 'Bearer ' .$token,
				'validateuser' => 'Bearer '.$account_token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			];
			$payload = ['payload'=>''];
			
			try {
				// Make API request with headers and JSON body
				$response = Http::withHeaders($headers)->post($apiUrl, $payload);

				// Log response
			   // Log::info('PayIn API Response:', ['response' => $response->body()]);
			   // Log::info('PayIn API Status Code:', ['status' => $response->status()]);

				// Parse API response
				$apiResponse = json_decode($response->body());
                
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					  return response()->json([
						  'status' => 200,
						  'message' => 'Game list..',
						   'data' =>$apiResponse->data,
						  'fish' =>$apiResponse->fish,
						  'slot' =>$apiResponse->slot,
						  'tableandcard' =>$apiResponse->tableandcard,
						  'crash' =>$apiResponse->crash
					  ], 200); 
				}

				// Handle API errors
				return response()->json(['status' => 400,'message' => 'Failed to get game list.', 'api_response' => $response->body()], 400);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status' => 400, 'message' => 'Internal Server Error','error' => $e->getMessage()], 400);
			}
		}
	
	public function get_game_url_old(Request $request){
                $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id',
								'game_id' => 'required'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		
				$user_id = $request->user_id;
				$game_id = $request->game_id;
		        $apiUrl = 'https://api.gamebridge.co.in/seller/v1//get-newjilli-game-url-by-gameid';
		        $account_token = DB::table('users')->where('id',$user_id)->value('accountNo');
		        $manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
		        $authorizationtoken='1740119423505';
		        $headers = [
							'authorization' => 'Bearer ' .$manager_key,
							'validateuser' => 'Bearer '.$account_token,
							'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
		        $headers = [
							'authorization' => 'Bearer ' .$manager_key,
							'validateuser' => 'Bearer '.$account_token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
						];
		       $pay_load = ['game_id'=>$game_id,'mobile'=>$account_token];
		       $pay_load = json_encode($pay_load);
		       $pay_load = base64_encode($pay_load);
		       $payloadpar = ['payload'=>$pay_load];  
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
			    //dd($apiResponse);
			    $data = $apiResponse->gameUrl;
		        $game_url = $data->gameUrl;
			
			
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					  return response()->json([
						  'status' => 200,
						  'message' => 'Game url..',
						  'game_url'=>$game_url,
						   //'data' =>$apiResponse->gameUrl,
					  ], 200); 
				}

				// Handle API errors
				return response()->json(['status' => 400,'message' => 'Failed to get game list.', 'api_response' => $response->body()], 400);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status' => 400, 'message' => 'Internal Server Error','error' => $e->getMessage()], 400);
			}
	}
	
	public function get_game_url(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'game_id' => 'required',
    ]);
    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
    }

    $user_id = $request->user_id;
    $game_id = $request->game_id;
<<<<<<< HEAD
	
 // First, update the wallet
    $this->update_jilli_wallets($request);
		
		
		
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    $apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-newjilli-game-url-by-gameid';
    $manager_key = 'FEGIScSYS3cMy';

    // Get the user's account token
    $account_token = DB::table('users')->where('id', $user_id)->value('accountNo');
    if (!$account_token) {
        return response()->json(['status' => 400, 'message' => 'Invalid account token.'], 400);
    }
<<<<<<< HEAD
    $authorizationtoken='1740119423505';
=======

>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    // Prepare headers and payload
    $headers = [
        'authorization' => 'Bearer ' . $manager_key,
        'validateuser' => 'Bearer ' . $account_token,
<<<<<<< HEAD
        'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
    ];

    $payload = base64_encode(json_encode(['gameId' => $game_id, 'mobile' => $account_token]));
    $payloadPar = ['payload' => $payload];

    try {
        // Send API request
        $response = Http::withHeaders($headers)->post($apiUrl, $payloadPar);
        $apiResponse = json_decode($response->body());

        // Check if the response and gameUrl exist
        if ($response->successful() && isset($apiResponse->error) && !$apiResponse->error) {
            if (isset($apiResponse->gameUrl)) {
                $game_url = $apiResponse->gameUrl;
                return response()->json([
                    'status' => 200,
                    'message' => 'Game URL retrieved successfully.',
                    'game_url' => $game_url,
                ], 200);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Game URL not found in the response.',
                ], 400);
            }
        }

        // Handle API error responses
        return response()->json([
            'status' => 400,
            'message' => 'Failed to get game URL.',
            'api_response' => $response->body(),
        ], 400);
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Get Game URL API Error:', ['error' => $e->getMessage()]);

        // Return an internal server error response
        return response()->json([
            'status' => 500,
            'message' => 'Internal Server Error',
            'error' => $e->getMessage(),
        ], 500);
    }
}

<<<<<<< HEAD
	public function update_jilli_wallets(Request $request){
		 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id',
		                    	//'amount'=>'required|numeric|gt:0'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		
		$user_id = $request->user_id;
		//$amount = $request->amount;
		$account_token = DB::table('users')
    ->where('id', $user_id)
    ->select('accountNo', 'winning_wallet')
    ->first();
		$userId=$account_token->accountNo;
		$wallet=$account_token->winning_wallet;

		//dd($userId,$wallet);
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/assign-same-new-jilli-wallet';
		$manager_key = 'FEGIScSYS3cMy';
		$authorizationtoken='1740119423505';
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$userId,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
			];
		$pay_load = ['amount'=>$wallet,'mobile'=>$userId];
		$pay_load = json_encode($pay_load);
		$pay_load = base64_encode($pay_load);
		$payloadpar = ['payload'=>$pay_load];
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
			   //dd($apiResponse);
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					return response()->json(['status'=>200,'message'=>$apiResponse->msg]);
				}
				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse->msg]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status'=>400,'message'=>$e->getMessage()]);
			}
	}
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
	
	
	public function get_jilli_transactons_details(Request $request){
                 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		$user_id = $request->user_id;
		$account_token = DB::table('users')->where('id',$user_id)->value('accountNo');
		//dd($account_token);
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-new-jilli-transaction-his';
	    $manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
	    $authorizationtoken='1740119423505';
	    $headers = [
					'authorization' => 'Bearer ' .$manager_key,
					'validateuser' => 'Bearer '.$account_token,
					'authorizationtoken' => 'Bearer '.$authorizationtoken
				   ];
		//dd($headers);
		 $payload = base64_encode(json_encode(['mobile' => $account_token]));
		//dd($payload);
=======
	    $headers = [
					'authorization' => 'Bearer ' .$manager_key,
					'validateuser' => 'Bearer '.$account_token
				   ];
		//dd($headers);
		 $payload = base64_encode(json_encode(['mobile' => $account_token]));
	//	dd($payload);
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
        $payloadpar = ['payload' => $payload];
		//$payloadpar = ['payload'=>''];
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
<<<<<<< HEAD
			    //dd($response);
				$apiResponse = json_decode($response->body());
			    //dd($apiResponse);
=======
			  //  dd($response);
				$apiResponse = json_decode($response->body());
			   // dd($apiResponse);
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					  return response()->json([
						  'status' => 200,
						  'message' => 'Transaction details..',
						  'data' =>$apiResponse->data
					  ], 200); 
				}
<<<<<<< HEAD

=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
				// Handle API errors
				return response()->json(['status' => 400,'message' => 'Failed to get transaction details.', 'api_response' => $response->body()], 400);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status' => 400, 'message' => 'Internal Server Error','error' => $e->getMessage()], 400);
			}
	}
	 //deduct-newjilliuser-wallet-by-id
	
	public function jilli_deduct_from_wallet(Request $request){  
		 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id',
			                     'amount'=>'required|numeric|gt:0'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		$user_id = $request->user_id;
		$amount = $request->amount;
		$account_token = DB::table('users')->where('id',$user_id)->value('accountNo');
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/deduct-newjilliuser-wallet-by-id';
	    $manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
	    $authorizationtoken='1740119423505';
	    $headers = [
					'authorization' => 'Bearer ' .$manager_key,
					'validateuser' => 'Bearer '.$account_token,
					'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
	    $headers = [
					'authorization' => 'Bearer ' .$manager_key,
					'validateuser' => 'Bearer '.$account_token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
				   ];
	
		       $pay_load = ['amount'=>$amount,'mobile'=>$account_token];
		       $pay_load = json_encode($pay_load);
		       $pay_load = base64_encode($pay_load);
		       $payloadpar = ['payload'=>$pay_load];
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
			   //dd($apiResponse);
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					  return response()->json(['status'=>200,'message'=>$apiResponse->msg]);
				}

				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse->msg]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status' => 400, 'message' => 'Internal Server Error','error' => $e->getMessage()], 400);
			}
	}
<<<<<<< HEAD
	
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
	public function jilli_get_bet_history(Request $request){
		 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
<<<<<<< HEAD
		
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
				$user_id = $request->user_id;
		        $apiUrl = 'https://api.gamebridge.co.in/seller/v1/jilli_get_bet_history';
		        $account_token = DB::table('users')->where('id',$user_id)->value('account_token');
		        $manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
		        $authorizationtoken='1740119423505';
		        $headers = [
							'authorization' => 'Bearer ' .$manager_key,
							'validateuser' => 'Bearer '.$account_token,
							'authorizationtoken' => 'Bearer '.$authorizationtoken
						];
		       $payloadpar = ['payload'=>''];  
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
			//dd($apiResponse);
			
=======
		        $headers = [
							'authorization' => 'Bearer ' .$manager_key,
							'validateuser' => 'Bearer '.$account_token
						];
		       $payloadpar = ['payload'=>''];  
		      // dd($payloadpar);
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				//dd($response);
				$apiResponse = json_decode($response->body());
		    //	dd($apiResponse);
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
				// Check if API call was successful
			   //if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
				if ($response->successful()) {
					  return response()->json([
						  'status' => 200,
						  'message' => 'Jilli bet history..',
						   'data' =>$apiResponse->data,
					  ], 200); 
				}

				// Handle API errors
				return response()->json(['status' => 400,'message' => 'Jili bet history.', 'api_response' => $response->body()], 400);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status' => 400, 'message' => 'Internal Server Error','error' => $e->getMessage()], 400);
			}
	}
	
	
	public function add_in_jilli_wallet(Request $request){
		 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id',
		                    	'amount'=>'required|numeric|gt:0'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		
		$user_id = $request->user_id;
		$amount = $request->amount;
		 $account_token = DB::table('users')->where('id',$user_id)->value('accountNo');
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/add-newjilliuser-wallet-by-id';
		$manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
		$authorizationtoken='1740119423505';
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$account_token,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$account_token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			];
		$pay_load = ['amount'=>$amount,'mobile'=>$account_token];
		$pay_load = json_encode($pay_load);
		$pay_load = base64_encode($pay_load);
		$payloadpar = ['payload'=>$pay_load];
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
			   //dd($apiResponse);
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					return response()->json(['status'=>200,'message'=>$apiResponse->msg]);
				}
				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse->msg]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status'=>400,'message'=>$e->getMessage()]);
			}
	}
	
	public function get_jilli_wallet(Request $request){
		 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		
		$user_id = $request->user_id;
		$amount = $request->amount;
		 $account_token = DB::table('users')->where('id',$user_id)->value('accountNo');
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-newjilliuser-wallet-by-id';
		$manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
		$authorizationtoken='1740119423505';
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$account_token,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$account_token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			];
		$pay_load = ['mobile'=>$account_token];
		$pay_load = json_encode($pay_load);
		$pay_load = base64_encode($pay_load);
		$payloadpar = ['payload'=>$pay_load];
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
			//dd($apiResponse);
			    $data = $apiResponse->data;

// Ensure $winning_wallet is defined properly
$winning_wallet = $data; // Assuming $data contains the required values

// Assign the values to $wallet and $winning_wallet
$wallet = $data[0]->njl_money;
$winning_wallet_value = $data[0]->njl_winning;

// Combine them into an array
$combined = [
    'wallet' => $wallet,
    'winning_wallet' => $winning_wallet_value,
];
<<<<<<< HEAD
dd($combined);
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263


			     //dd($data);
			     
			   //dd($apiResponse->data);
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					return response()->json(['status'=>200,'message'=>$apiResponse->msg,'data'=>$combined]);
				}
				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse->msg]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status'=>400,'message'=>$e->getMessage()]);
			}
	}
	
	
	
	public function update_main_wallet(Request $request)
	{
	 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id',
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		
		$user_id = $request->user_id;
		
		$token=User::where('id',$user_id)->first();
		$user_token=$token->account_token;
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-user-info';
		$manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
		$authorizationtoken='1740119423505';
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$user_token,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$user_token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			];
		$payloadpar = ['payload'=>''];
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
<<<<<<< HEAD
			$money=$apiResponse->money;
			//dd($money);
=======
				dd($apiResponse);
		    	$money=$apiResponse->money;
			dd($money);
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					$update=User::where('id', $user_id)->update(['wallet' => $money]);
					return response()->json(['status'=>200,'message'=>$apiResponse->msg,'Updated_money'=>$money]);
				}
				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse->msg]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status'=>400,'message'=>$e->getMessage()]);
			}
		
	}
	
	
	public function all_game_list_test(Request $request){
			
			$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-all-games-list';
			$token = 'FEGIScSYS3cMy';
<<<<<<< HEAD
			$authorizationtoken='1740119423505';
			
			$headers = [
				'authorization' => 'Bearer ' .$token,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
			
			$headers = [
				'authorization' => 'Bearer ' .$token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			];
			$payload = ['payload'=>''];
			
			try {
				// Make API request with headers and JSON body
				$response = Http::withHeaders($headers)->post($apiUrl, $payload);
				//dd($response);
				// Parse API response
				$apiResponse = json_decode($response->body());
				//dd($apiResponse);
                
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					  return response()->json([
						  'status' => 200,
						  'message' => 'Game list..',
						   'data' =>$apiResponse->data,
						  'fish' =>$apiResponse->fish,
						  'slot' =>$apiResponse->slot,
						  'tableandcard' =>$apiResponse->tableandcard,
						  'crash' =>$apiResponse->crash
					  ], 200); 
				}

				// Handle API errors
				return response()->json(['status' => 400,'message' => 'Failed to get game list.', 'api_response' => $response->body()], 400);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status' => 400, 'message' => 'Internal Server Error','error' => $e->getMessage()], 400);
			}
		}
	
	public function update_jilli_wallet(Request $request){
		 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id',
		                    	//'amount'=>'required|numeric|gt:0'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		
		$user_id = $request->user_id;
		//$amount = $request->amount;
		$account_token = DB::table('users')
    ->where('id', $user_id)
    ->select('accountNo', 'wallet')
    ->first();
<<<<<<< HEAD
    //dd($account_token);
		$userId=$account_token->accountNo;
		$wallet=$account_token->wallet;
		//dd($wallet,$userId);
=======
		$userId=$account_token->accountNo;
		$wallet=$account_token->wallet;
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263

		//dd($userId,$wallet);
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/assign-same-new-jilli-wallet';
		$manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
		$authorizationtoken='1740119423505';
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$userId,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
			];
		$pay_load = ['amount'=>$wallet,'mobile'=>$userId];
		//dd($pay_load);
		$pay_load = json_encode($pay_load);
		$pay_load = base64_encode($pay_load);
		$payloadpar = ['payload'=>$pay_load];
		//dd($payloadpar);
=======
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$userId
			];
		$pay_load = ['amount'=>$wallet,'mobile'=>$userId];
		$pay_load = json_encode($pay_load);
		$pay_load = base64_encode($pay_load);
		$payloadpar = ['payload'=>$pay_load];
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
<<<<<<< HEAD
			  // dd($apiResponse);
=======
			   //dd($apiResponse);
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
				// Check if API call was successful
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
					return response()->json(['status'=>200,'message'=>$apiResponse->msg]);
				}
				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse->msg]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status'=>400,'message'=>$e->getMessage()]);
			}
	}
	
	public function update_jilli_to_user_wallet(Request $request){
		 $validator = Validator::make($request->all(), [
								'user_id' => 'required|exists:users,id'
							]);
				$validator->stopOnFirstFailure();
				if ($validator->fails()) {
					return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
				} 
		
		$user_id = $request->user_id;
		//$amount = $request->amount;
		 $account_token = DB::table('users')->where('id',$user_id)->value('accountNo');
		$apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-newjilliuser-wallet-by-id';
		$manager_key = 'FEGIScSYS3cMy';
<<<<<<< HEAD
		$authorizationtoken='1740119423505';
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$account_token,
				'authorizationtoken' => 'Bearer '.$authorizationtoken
=======
	    $headers = [
				'authorization' => 'Bearer ' .$manager_key,
				'validateuser' => 'Bearer '.$account_token
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			];
		$pay_load = ['mobile'=>$account_token];
		$pay_load = json_encode($pay_load);
		$pay_load = base64_encode($pay_load);
		$payloadpar = ['payload'=>$pay_load];
		
		try {
				$response = Http::withHeaders($headers)->post($apiUrl, $payloadpar);
				$apiResponse = json_decode($response->body());
			//dd($apiResponse);
			    $data = $apiResponse->data;

// Ensure $winning_wallet is defined properly
$winning_wallet = $data; // Assuming $data contains the required values

// Assign the values to $wallet and $winning_wallet
$wallet = $data[0]->njl_money;
$winning_wallet_value = $data[0]->njl_winning;

// Combine them into an array
//$combined = [
//    'wallet' => $wallet,
//    'winning_wallet' => $winning_wallet_value,
//	];
<<<<<<< HEAD
// code by RK sharma start  here code & this code for users table 

    $currentWallet = DB::table('users')->where('id', $user_id)->value('wallet');
    $current_winning_Wallet = DB::table('users')->where('id', $user_id)->value('winning_wallet');
    $actual_winning_wallet = $winning_wallet_value - $currentWallet;
   if ($actual_winning_wallet < 0) {
    // If actual_winning_wallet is a negative value and more than current_winning_Wallet, set to 0
    if (abs($actual_winning_wallet) > $current_winning_Wallet) {
        DB::table('users')
            ->where('id', $user_id)
            ->update([
                'winning_wallet' => 0
            ]);
    } else {
        // Otherwise, subtract the absolute value of actual_winning_wallet from current_winning_Wallet
        DB::table('users')
            ->where('id', $user_id)
            ->update([
                'winning_wallet' => DB::raw("winning_wallet - " . abs($actual_winning_wallet))
            ]);
            }
        } else {
            // If actual_winning_wallet is positive, add to current_winning_Wallet
            DB::table('users')
                ->where('id', $user_id)
                ->update([
                    'winning_wallet' => DB::raw("winning_wallet + $actual_winning_wallet")
                ]);
        }

// code by RK sharma end here code & this code for users table 
     
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
			DB::table('users')
    ->where('id', $user_id) // Find the user by ID
    ->update(['wallet' => $winning_wallet_value]); // Update the wallet field



			     //dd($data);
			     
			   //dd($apiResponse->data);
				// Check if API call was successful
<<<<<<< HEAD
			if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
=======
				if ($response->successful() && isset($apiResponse->error) && $apiResponse->error == false) {
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
					return response()->json(['status'=>200,'message'=>$apiResponse->msg,'data'=>$combined]);
				}
				// Handle API errors
				return response()->json(['status'=>400,'message'=>$apiResponse->msg]);
			} catch (\Exception $e) {
				// Log exception
				Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
				// Return server error response
				return response()->json(['status'=>400,'message'=>$e->getMessage()]);
			}
	}
	
	
	
	
}







