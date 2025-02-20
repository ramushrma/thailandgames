<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;

class UsdtDepositController extends Controller
{
    public function usdt_deposit_index(string $id)
    {
       
         $deposits= DB::select("SELECT payins.*,users.name AS uname,users.id As userid, users.mobile As mobile FROM `payins` LEFT JOIN 
users ON payins.user_id=users.id WHERE payins.status = '$id' && payins.type=1");

        return view('usdt_deposit.deposit')->with('deposits',$deposits)->with('id',$id);
    }
  

// public function usdt_success(string $id) {
//     // Fetch the details
//     $details = DB::table('payins')->where('id', $id)->first();
// //	 dd($details);
//     // Check if details exist
//     if (!$details) {
//         return redirect()->back()->with('error', 'Payin details not found.');
//     }
// 	$userid = $details->user_id;
// //	dd($userid);
// 	if($details->type==3){
//     	$amount = $details->usdt_amount;
// 	}elseif($details->type==2){
// 		$amount = $details->cash;
// 	}
// 	$userdata = DB::table('users')->where('id',$userid)->where('status',1)->first();
	
// 	 if (!$userdata){
//         return redirect()->back()->with('error', 'User block by Admin');
//     }
// 	$first_recharge = $userdata->first_recharge;
//   //dd($first_recharge);
//     $users = User::where('id',$details->user_id)->first();
//     if(!$users){
//         return redirect()->back()->with('error', 'user not found.');
//     }
// 		$referral_user = DB::table('users')->where('id', $userid)->value('referrer_id');
// 		dd($referral_user);
// 			 if($first_recharge == '1'){
// 				 $first_deposit_bonus = DB::table('first_deposit_bonus')
//     					->where('recharge_min', '<=', $amount)
//     					->where('recharge_max', '>=', $amount)
//     					->first();
				 
// 				 $first_deposit_bonus->member;
// 				 $first_deposit_bonus->agent;
				
// 			 	 if($amount >= 200){ $first_recharge_status = 0; } else { $first_recharge_status = 1;}
				
// 				 $data2 = DB::table('users')
//     				->where('id', $userid)
//     				->update([
//         					'wallet' => DB::raw("wallet + $amount + $first_deposit_bonus->member"),	
// 						    'recharge' => DB::raw("recharge + $amount + $first_deposit_bonus->member"),	
// 						    'first_recharge' => $first_recharge_status
						  
//     					]);
				 
// 				   DB::table('users')
//     				->where('id', $referral_user)
//     				->update([
//         					'wallet' => DB::raw("wallet + $first_deposit_bonus->agent"),
// 						    'recharge' => DB::raw("recharge + $first_deposit_bonus->agent"),
//     					]);
// 	  $insert= DB::table('wallet_history')->insert([
//         'userid' => $userid,
//         'amount' => $first_deposit_bonus->member,
//         'subtypeid' => 10,
// 		'created_at'=> now(),
//         'updated_at' => now()
		
//     ]);
				 
// 	$insert= DB::table('wallet_history')->insert([
//         'userid' => $referral_user,
//         'amount' => $first_deposit_bonus->agent,
//         'subtypeid' => 10,
// 		'created_at'=> now(),
//         'updated_at' => now()
		
//     ]);
//     // Update the payin status
//     DB::table('payins')->where('id', $id)->update([
//         'status' => 2
//     ]);
//     // Insert into wallet history
//     DB::table('wallet_history')->insert([
//         'userid' => $userid,
//         'amount' => $amount,
//         'subtypeid' => 26
//     ]);
//     return redirect()->back()->with('success', 'Successfully Updated.');
// }elseif($first_recharge == '0'){
// 			   $data2 = DB::table('users')->where('id', $userid)
// 				   	->update([
//         					'wallet' => DB::raw("wallet + $amount"),	
// 						    'recharge' => DB::raw("recharge + $amount")
//     					]);
    					
    					
//  DB::table('payins')->where('id', $id)->update([
//         'status' => 2
//     ]);
				
// 			return redirect()->back()->with('success', 'Successfully Updated.');
		
				 
//  }
// 			 }
    public function usdt_success(string $id){
        $payins_data = DB::table('payins')->where('id', $id)->first();
        if (!$payins_data) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }
        if ($payins_data->status != 1) {
            return redirect()->back()->with('error', 'Already Updated.');
        }
        $user_id = $payins_data->user_id;
        $cash_recharge = $payins_data->cash;
        $user_data = DB::table('users')->where('id', $user_id)->first();
        if (!$user_data) {
            return redirect()->back()->with('error', 'User not found.');
        }
        DB::table('users')->where('id', $user_id)->increment('wallet', $cash_recharge);
        DB::table('users')->where('id', $user_id)->increment('deposit_amount', $cash_recharge);
        DB::table('payins')->where('id', $id)->update([
            "status" => 2,
            "updated_at" => now()
        ]);
        $insert = DB::table('wallet_histories')->insert([
            'user_id' => $user_id,
            'amount' => $cash_recharge,
            'description' => "USDT Manual",
            'type_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return redirect()->back()->with('success', 'Amount credited to your main wallet.');
    }

    //   public function usdt_reject(string $id){
    //             DB::table('payins')->where('id', $id)->update([
    //                     'status' => 3
    //             ]);
    //             return redirect()->back()->with('success', 'Successfully Updated.');
    //     }
        
        
        public function rejectPayin(Request $request){
          
        $request->validate([
            'reason' => 'required|string|max:55',
        ]);
     //  dd($request->id);
        DB::table('payins')->where('id', $request->id)->update([
            'status' => 3,
            'reason' => $request->reason,
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Payin rejected successfully.');
    }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        // offline payment
        
         public function offline_deposit_index(string $id){

         $deposits= DB::select("SELECT payins.*,users.username AS uname,users.id As userid, users.mobile As mobile FROM `payins` LEFT JOIN 
users ON payins.user_id=users.id WHERE payins.status = '$id' && payins.type=3");

        return view('usdt_deposit.deposit')->with('deposits',$deposits)->with('id',$id);
    }


}

