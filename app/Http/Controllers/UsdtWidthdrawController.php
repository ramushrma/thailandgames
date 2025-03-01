<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class UsdtWidthdrawController extends Controller
{
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
             
        if ($request->session()->has('id')) {
            // Use Query Builder to perform the update safely
            DB::table('withdraw_histories')
                ->where('status', 1)
                ->update([
                    'status' => 2,
                    'perform_id' => $auth_id,
                    'perform_role' => $perform_role
                    ]);

            // Retrieve updated withdrawal histories
            $widthdrawls = DB::table('withdraw_histories')->get();

            // Return the view with the updated data
            return view('widthdrawl.index', compact('widthdrawls'))->with('id', '1');
        } else {
            // Redirect to login if session does not have 'id'
            return redirect()->route('login');
        }
    }
    
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
}
