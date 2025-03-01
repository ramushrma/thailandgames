<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class UsdtWidthdrawController extends Controller
{
<<<<<<< HEAD
    public function usdt_widthdrawl_index(Request $request, $id){
    $authuser = $request->session()->get('authuser');
    $auth_role = $authuser->role_id;
    $auth_uid = $authuser->id;

    if ($auth_role == 2) {
        $widthdrawls = DB::select("SELECT withdraws.*, users.name AS uname, users.mobile AS mobile, 
               user_usdt_address.name AS beneficiary_name, 
               user_usdt_address.usdt_address AS user_usdt_address 
        FROM withdraws 
        JOIN users ON withdraws.user_id = users.id 
        JOIN user_usdt_address ON withdraws.user_id = user_usdt_address.userid 
        WHERE withdraws.type = 1 AND withdraws.status = $id
        AND (users.admin_id = '$auth_uid' OR users.id = '$auth_uid' OR users.vendor_id IN (SELECT id FROM users WHERE admin_id = '$auth_uid'))");
    } elseif ($auth_role == 3) {
        $widthdrawls = DB::select("SELECT withdraws.*, users.name AS uname, users.mobile AS mobile, 
               user_usdt_address.name AS beneficiary_name, 
               user_usdt_address.usdt_address AS user_usdt_address 
        FROM withdraws 
        JOIN users ON withdraws.user_id = users.id 
        JOIN user_usdt_address ON withdraws.user_id = user_usdt_address.userid 
        WHERE withdraws.type = 1 AND withdraws.status = $id
        AND users.vendor_id = '$auth_uid'");
    } else {
        $widthdrawls = DB::select("SELECT withdraws.*, users.name AS uname, users.mobile AS mobile, 
               user_usdt_address.name AS beneficiary_name, 
               user_usdt_address.usdt_address AS user_usdt_address 
        FROM withdraws 
        JOIN users ON withdraws.user_id = users.id 
        JOIN user_usdt_address ON withdraws.user_id = user_usdt_address.userid 
        WHERE withdraws.type = 1 AND withdraws.status = $id");
    }

    return view('usdt_withdraw.index', compact('widthdrawls'))->with('id', $id);
}


    public function usdt_success(Request $request , $id){
           $authuser = $request->session()->get('authuser');
           $auth_role = $authuser->role_id; 
           $auth_id = $authuser->id;
           
           $description = null;
           if($auth_role ==1){
              $perform_role = "Super Admin";
           }
           if($auth_role ==2){
              $perform_role = " Admin";
           }
           if($auth_role ==3){
              $perform_role = "Vendor";
           }
           
            DB::table('withdraws')
                ->where('id', $id)
                ->update([
                    'status' => 2,
                    'perform_id' => $auth_id,
                    'perform_role' => $perform_role
                    ]);
           return back()->with('success', 'Successfully updated.');
    }
    
   public function usdt_reject(Request $request){
       //dd($request->all());
       
           $authuser = $request->session()->get('authuser');
           $auth_role = $authuser->role_id; 
           $auth_id = $authuser->id;
           $description = null;
           
           if($auth_role ==1){
              $perform_role = "Super Admin";
           }
           if($auth_role ==2){
              $perform_role = " Admin";
           }
           if($auth_role ==3){
              $perform_role = "Vendor";
           }
      
       $data = DB::table('withdraws')
              ->where('id', $request->id)
              ->update([
                  'status' => '3',
                 'perform_id' => $auth_id,
                 'perform_role' => $perform_role,
                 'reason' =>  $request->reason,
                 'updated_at' => now()
              ]);

    return $data 
        ? back()->with('success', 'Successfully updated.') 
        : back()->with('error', 'Update failed.');
}

        
    public function all_success(Request $request)
    {
        // Check if the session has an 'id' key
        
           $authuser = $request->session()->get('authuser');
           $auth_role = $authuser->role_id; 
           $auth_id = $authuser->id;
           
           $description = null;
           if($auth_role ==1){
              $perform_role = "Super Admin";
           }
           if($auth_role ==2){
              $perform_role = " Admin";
           }
           if($auth_role ==3){
              $perform_role = "Vendor";
           }
             
=======
    public function usdt_widthdrawl_index($id)
    {
		//dd($id);
        // Fetch all records from the Project_maintenance model
        $widthdrawls = DB::select("SELECT withdraw_histories.*, users.name AS uname, users.mobile AS mobile, usdt_account_deatils.name as  beneficiary_name FROM withdraw_histories JOIN users ON withdraw_histories.user_id = users.id JOIN usdt_account_deatils ON withdraw_histories.`account_id` = usdt_account_deatils.id where withdraw_histories.type=1 && withdraw_histories.status=$id
");

        // Pass the data to the view and load the 'usdt_withdraw.index' Blade file
        return view('usdt_withdraw.index', compact('widthdrawls'))->with('id', $id);
    }

    public function usdt_success(Request $request, $id)
    {
        // Check if the session has an 'id' key
        if ($request->session()->has('id')) {
            // Use parameter binding to prevent SQL injection
            DB::table('withdraw_histories')
                ->where('id', $id)
                ->update(['status' => 2]);

            // Redirect with route and parameters
            return redirect()->route('usdt_widthdrawl', ['status' => 1])->with('key', 'value');
        } else {
            // Redirect to login if session does not have 'id'
            return redirect()->route('login');
        }
    }

    public function usdt_reject(Request $request, $id)
    {
        // Retrieve the withdrawal history for the given id
        $data = DB::table('withdraw_histories')->where('id', $id)->first();
        
        // If no data is found, handle it appropriately
        if (!$data) {
            // Handle the case where no withdrawal history is found
            return redirect()->route('usdt_widthdrawl', ['status' => 1])->with('error', 'Withdrawal history not found.');
        }

        $amt = $data->amount;
        $useid = $data->user_id;

        // Check if the session has an 'id' key
        if ($request->session()->has('id')) {
            // Use Query Builder to perform updates safely
            DB::table('withdraw_histories')->where('id', $id)->update(['status' => 3]);
            DB::table('users')->where('id', $useid)->increment('wallet', $amt);
            
            // Redirect with route and parameters
            return redirect()->route('usdt_widthdrawl', ['status' => 1])->with('key', 'value');
        } else {
            // Redirect to login if session does not have 'id'
            return redirect()->route('login');
        }
    }

    public function all_success(Request $request)
    {
        // Check if the session has an 'id' key
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
        if ($request->session()->has('id')) {
            // Use Query Builder to perform the update safely
            DB::table('withdraw_histories')
                ->where('status', 1)
<<<<<<< HEAD
                ->update([
                    'status' => 2,
                    'perform_id' => $auth_id,
                    'perform_role' => $perform_role
                    ]);
=======
                ->update(['status' => 2]);
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263

            // Retrieve updated withdrawal histories
            $widthdrawls = DB::table('withdraw_histories')->get();

            // Return the view with the updated data
            return view('widthdrawl.index', compact('widthdrawls'))->with('id', '1');
        } else {
            // Redirect to login if session does not have 'id'
            return redirect()->route('login');
        }
    }
<<<<<<< HEAD
    
    public function withdrawal_charges(){
        $data = DB::table('withdrawal_charges')->get();
       return view('widthdrawl.charges')->with('data', $data);
    }
    
    
  public function edit_charges(Request $request, $id) {
    $request->validate([
        'charges' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/']
    ]);
 //  dd($request->all());
    $notification = DB::table('withdrawal_charges')->where('id', $id)->update([
        'charges' => $request->charges,
        'updated_at' => now()
    ]);
    if ($notification) {
        return redirect()->back()->with('success', 'charges updated successfully!');
    } else {
        return redirect()->back()->with('error', 'Failed to update charges.');
    }
    }
=======
>>>>>>> 7b570b3acf7925bce6e596785d2268af1a197263
}
