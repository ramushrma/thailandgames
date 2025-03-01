<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
<<<<<<< HEAD
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
 
class PayingController extends Controller{
    
   public function paywalex(Request $request){
       $validator = Validator::make($request->all(),[
             'amount' => 'required|numeric|min:1'
            ]);
            $validator->stopOnFirstFailure();
            if($validator->fails()){
                 $response = [
                                'status' => 400,
                                'message' => $validator->errors()->first()
                              ]; 
                return response()->json($response, 200);
            }
        $data = DB::table('users')->where('id', $request->userid)->first();
        $amount = $request->amount;
        $name = $data->name;
        $mobile = $data->mobile;
        $email   = $data->email;
        $orderNumber = time() . rand(1000000, 9999999);
<<<<<<< HEAD
        dd($email,$name,$mobile,$amount,$orderNumber);
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
       // dd($email,$name,$mobile,$amount,$orderNumber);
        $body = [
				'amount' => "$amount",
				'name'=>"$name",
				'email'=>"$email",
				'mobile'=>"$mobile",
				'udf1' => "$orderNumber",
				'udf2' => '',
				'udf3' => ''
			];
			$body=json_encode($body);
           	$curl = curl_init();
			curl_setopt_array($curl, [
			  CURLOPT_URL => "https://login.paywalex.com/api/v1/payin/create_intent",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>$body,
			  CURLOPT_HTTPHEADER => [
				"Authorization: bTcNT4WdpWC7nmEVdbDYL76EvLI0hq",
				"accept: application/json",
				"content-type: application/json"
			  ],
			]);
             
			$response = curl_exec($curl);
			dd($response);
			$err = curl_error($curl);
			curl_close($curl);
               
   }
<<<<<<< HEAD
   
   	public function payin_usdt(Request $request)
{
    // Validation
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'amount' => 'required|numeric|gt:0',
        'type' => 'required|in:0',
    ]);
//  dd($request->all());
    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
    }
   
    // Get input data
    $user_id = $request->user_id;
    $amount = $request->amount;
    $type = $request->type;
    $inr_amt = $amount * 94;
    
    // Get client IP address
   // $clientIp = $request->ip();

    // Dump and die to see IP address
    // ('Client IP Address:', $clientIp) // Here, you can see the IP

    $email = 'Globalbettech@gmail.com'; 
    $token = '58839776549046321236110964258208'; // Replace with a secure token or config value
    $apiUrl = "https://cryptofit.biz/Payment/coinpayments_api_call";
    $coin = 'USDT.BEP20';

    // Generate unique order ID
    do {
        $orderId = str_pad(mt_rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
    } while (DB::table('payins')->where('order_id', $orderId)->exists());
    // User validation
    $user_exist = DB::table('users')->where('id', $user_id)->first();

    // Prepare API data
    $formData = [
        'txtamount' => $amount,
        'coin' => $coin,
        'UserID' => $email,
        'Token' => $token,
        'TransactionID' => $orderId,
    ];

    try {
        // Make API request
        $response = Http::asForm()->post($apiUrl, $formData);

        Log::info('PayIn API Response:', ['response' => $response->body()]);
        Log::info('PayIn API Status Code:', ['status' => $response->status()]);

        // Decode the response
        $apiResponse = json_decode($response->body());
        //dd($apiResponse); // You can dump API response here

        // Check if the API response is successful
        if ($response->successful() && isset($apiResponse->error) && $apiResponse->error === 'ok') {
            // Insert data into payins table
            $inserted_id = DB::table('payins')->insertGetId([
                'user_id' => $user_id,
                'status' => 1,
                'order_id' => $orderId,
                'cash' => $inr_amt,
                'usdt_amount' => $amount,
                'type' => $type,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Payment initiated successfully.',
                'data' => $apiResponse,
            ], 200);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Failed to initiate payment.'
        ], 400);
    } catch (\Exception $e) {
        Log::error('PayIn API Error:', ['error' => $e->getMessage()]);
        return response()->json(['status' => 400, 'message' => 'Internal Server Error'], 400);
    }
}

   
    public function payin_call_back(Request $request)
{
    // Validation
    $validator = Validator::make($request->all(), [
        'invoice' => 'required',
        'status_text' => 'required',
        'amount' => 'required'
    ]);

    $validator->stopOnFirstFailure();

    if ($validator->fails()) {
        return response()->json(['status' => 400, 'message' => $validator->errors()->first()], 200);
    }

    // Get input data
    $invoice = $request->invoice;
    $status_text = $request->status_text;
    $amount = $request->amount;

    // Get client IP address
    $clientIp = $request->ip();

    // Dump and die to see IP address
    dd('Client IP Address:', $clientIp); // Here, you can see the IP

    if ($status_text == 'complete') {
        // Update payment status
        $a = DB::table('payins')->where('order_id', $invoice)->update(['status' => 2]);

        if ($a) {
            // Get user details
            $user_detail = Payin::where('order_id', $invoice)
                ->where('status', 2)
                ->first();

            $user_id = $user_detail->user_id;
            $amount1 = $user_detail->cash;

            // Update wallet balance
            $update = User::where('id', $user_id)->update(['wallet' => $amount1]);

            return response()->json(['status' => 200, 'message' => 'Payment successful.'], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'Failed to update!'], 400);
        }
    } else {
        return response()->json(['status' => 400, 'message' => 'Something went wrong!'], 400);
    }
}  
   
     public function pay_usdt(){
         $data = DB::table('manual_usdt')->get();
         if($data){
             return response()->json([
                 "status" => 200,
                 "data" => $data
                 ],200);
         }else{
             return response()->json([
                 "status" => 400,
                 "data" => []
                 ],200);
         }
     }
     
    //     public function uploadScreenshot(Request $request){
    //     $request->validate([
    //         'userid' => 'required|integer',
    //         'amount' => 'required|numeric',
    //         'usdt_amount' => 'required|numeric',
    //         'screenshot' => 'required|string', 
    //     ]);
    //     try {
    //         $imageData = $request->input('screenshot');
    //         $imageName = 'screenshot_' . time() . '.png';
    //         $folderPath = public_path('QRimage');
    //          do{
    //         $orderNumber = time() . rand(1000000, 9999999);
    //         $exists = DB::table('user_payment_screenshots')->where('order_numer', $orderNumber)->exists();
    //         } while ($exists);
    //         if (!File::exists($folderPath)) {
    //             File::makeDirectory($folderPath, 0755, true, true);
    //         }
    //         $imagePath = $folderPath . '/' . $imageName;
    //         file_put_contents($imagePath, base64_decode($imageData));
    //         $imageURL = url('QRimage/' . $imageName);
    //         $screenshotId = DB::table('user_payment_screenshots')->insertGetId([
    //             'userid' => $request->userid,
    //             'amount' => $request->amount,
    //             'usdt_amount' => $request->usdt_amount,
    //             'order_numer' => $orderNumber,
    //             'screenshot' => $imageURL,
    //             'status' => 1,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Screenshot uploaded successfully',
    //             'screenshot' => $imageURL,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => 400,
    //             'message' => 'Error uploading screenshot',
    //             'error' => $e->getMessage(),
    //         ], 200);
    //     }
    // }
    
     public function uploadScreenshot(Request $request){
       //  dd($request-all());
        $request->validate([
            'userid' => 'required|integer',
            'amount' => 'required|numeric',
            'usdt_amount' => 'required|numeric',
            'screenshot' => 'required|string',
        ]);
        $orderNumber = time() . rand(1000000, 9999999);
        $exists = DB::table('payins')->where('order_id', $orderNumber)->exists();
        if(!empty($exists)){
            $orderNumber = time() . rand(1000000, 9999999);
        }
    $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
    $imageData = $request->input('screenshot');
    $imageName = 'screenshot_' . time() . '.png';
    $folderPath = public_path('QRimage');
    if (!File::exists($folderPath)) {
        if (!File::makeDirectory($folderPath, 0755, true, true)) {
            return response()->json([
                'status' => 400,
                'message' => 'Failed to create directory'
            ], 200);
        }
    }
    $imagePath = $folderPath . '/' . $imageName;
    if (!file_put_contents($imagePath, base64_decode($imageData))) {
        return response()->json([
            'status' => 400,
            'message' => 'Failed to save screenshot'
        ], 200);
    }
    $imageURL = url('QRimage/' . $imageName);
    $screenshotId = DB::table('payins')->insertGetId([
        'user_id' => $request->userid,
        'cash' => $request->amount,
        'usdt_amount' => $request->usdt_amount,
        'order_id' => $orderNumber,
        'screenshot' => $imageURL,
        'status' => 1,
        'type' => 1,
        'created_at' => $currentDate,
        'updated_at' => $currentDate
    ]);
     $wallet_histories = DB::table('wallet_histories')->insert([
         "user_id" =>$request->userid,
         "amount" => $request->amount,
         "type_id" => 2,
         "description" => "Payin ",
         "created_at" => $currentDate
         ]);
  // dd($screenshotId);
    // If insertion fails
    if (!$screenshotId){
        return response()->json([
            'status' => 400,
            'message' => 'Failed to save screenshot details in database'
        ], 200);
    }
    return response()->json([
        'status' => 200,
        'message' => 'Screenshot uploaded successfully',
        'screenshot' => $imageURL,
    ], 200);
}

     
    public function userusdtaddress(Request $request) {
    $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s');
    $validator = Validator::make($request->all(), [
        'userid'       => 'required|integer',
        'name'         => 'required|string|max:255',
        'usdt_address' => 'required|string|max:255'
    ])->stopOnFirstFailure();
    if ($validator->fails()) {
        return response()->json([
            'status'  => 400,
            'message' => $validator->errors()->first()
        ], 200);
    }
    $check = DB::table('user_usdt_address')->where('userid', $request->userid)->first();
    if (!$check){
        $result = DB::table('user_usdt_address')->insert([
            "userid"       => $request->userid,
            "name"         => $request->name,
            "usdt_address" => $request->usdt_address,
            "create_at"   => $currentDate,
            "update_at"   => $currentDate
        ]);

        if ($result) {
            return response()->json([
                'status'  => 200,
                'message' => "USDT address added successfully"
            ], 200);
        }
    } else {
        $update = DB::table('user_usdt_address')->where('userid', $request->userid)->update([
            "name"         => $request->name,
            "usdt_address" => $request->usdt_address,
            "update_at"   => $currentDate
        ]);

        if ($update) {
            return response()->json([
                'status'  => 200,
                'message' => "USDT address updated successfully"
            ], 200);
        }
    }

    return response()->json([
        'status'  => 500,
        'message' => "Something went wrong, please try again"
    ], 500);
}

      public function viewUserUsdtAddress($userid) {
          //dd($userid);
            $userUsdt = DB::table('user_usdt_address')->where('userid',$userid)->first();
            if ($userUsdt) {
                return response()->json([
                    'status' => 200,
                    'data'   => $userUsdt
                ], 200);
            } else {
                return response()->json([
                    'status'  => 400,
                    'message' => "No USDT address found for this user"
                ], 200);
            }
    }
   
    //  public function depositHistories(Request $request){
    //     $query = DB::table('user_payment_screenshots')
    //             ->where('userid', $request->userid)
    //             ->select('amount','status','type','usdt_amount','order_numer','created_at');
    //         if ($request->type){
    //             $query->where('type', $request->type);
    //         } 
    //          if ($request->status){
    //             $query->where('status', $request->status);
    //         } 
    //         if ($request->date) {
    //             $query->whereDate('created_at', '=', $request->date);
    //         }
    //         $query->orderBy('created_at', 'desc');
    //         $result = $query->get();
    //         if ($result->isEmpty()) {
    //             return response()->json([
    //                 "status" => 400,
    //                 "data" => []
    //             ], 200);
    //         }
    //         return response()->json([
    //             "status" => 200,
    //             "data" => $result
    //         ], 200);
    //  }
        public function depositHistories(Request $request) {
           $type = $request->type ?? 1;
        $query = DB::table('payins')
            ->where('user_id', $request->userid)
            ->select(
                'cash as amount',
                'status as status',
                'type as type',
                'reason as reason',
                'usdt_amount as usdt_amount',
                'order_id as order_number',
                'created_at as created_at'
            );
    
        if ($type) {
            $query->where('type', $type);
        } 
        if ($request->status) {
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


=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
}