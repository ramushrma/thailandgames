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
}