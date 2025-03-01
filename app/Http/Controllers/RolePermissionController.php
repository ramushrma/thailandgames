<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\{User, Permission, Bet, Card, AdminWinnerResult, Betlog, GameSetting, VirtualGame, BetResult, MineGameBet, PlinkoBet, PlinkoIndexList, CustomerService};
use App\Helper\jilli;

class RolePermissionController extends Controller
{
    
    private function generateSecureRandomString($length = 6)
    {
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, $length);
    }
    public function create_role(Request $request) { 
        $data  = DB::table('permissions')->get();
        $authuser = $request->session()->get('authuser');
        $auth_role = $authuser->role_id; 
        $auth_uid = $authuser->id;
        
        $permissions = DB::table('users')->where('id', $auth_uid)->value('permissions');
        $permissionIds = json_decode($permissions, true);
        //dd($permissionIds);
        if (!is_array($permissionIds)) {
            $permissionIds = explode(',', $permissions);
        }
        $data = DB::table('permissions')->whereIn('id', $permissionIds)->get();
        if($auth_role ==3){
            $data = null;
        }
        return view('roles.create')
        ->with('data', $data)
        ->with('auth',$auth_role)
        ->with('id',$auth_uid);
    }
    
 public function getDependentRoles(Request $request)
{
    // Agar AJAX se `admin_id` bheja gaya hai toh filter karo
    if ($request->has('admin_id')) {
        $adminId = $request->input('admin_id');

        // Sirf wahi vendors fetch karo jinka created_by is adminId se match karta ho
        $vendorRoles = DB::table('users')->where('admin_id', $adminId)->select('id', 'name')->get();

        return response()->json([
            'vendor' => $vendorRoles
        ]);
    }

    // Agar `admin_id` nahi bheja gaya toh purana logic use karo
    $authUser = $request->session()->get('authuser');

    if (!$authUser) {
        return response()->json(['error' => 'User not found in session'], 401);
    }
    $auth_id = $authUser->id; 
    $role_id = $authUser->role_id;
    $adminRoles = [];
    $vendorRoles = [];
    if ($auth_id == 1) {
        $adminRoles = DB::table('users')->where('role_id', 2)->select('id', 'name')->get();
        $vendorRoles = DB::table('users')->where('role_id', 3)->select('id', 'name')->get();
    } else {
        $vendorRoles = DB::table('users')->where('admin_id', $auth_id)->select('id', 'name')->get();
    }

    return response()->json([
        'admin' => $adminRoles,
        'vendor' => $vendorRoles,
    ]);
 }
   
   
  
 public function createrole(Request $request)
{ 
   // dd($request->all());
    $validator = Validator::make($request->all(), [
        'selected_role' => 'required|',
        'email' => 'required|email|unique:users,email',
        'mobile' => 'required|numeric|digits:10|unique:users,mobile',
        'password' => 'required|regex:/^\d{6,}$/',
        'confirm_password' => 'required|same:password',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $selected_role  = $request->selected_role;
    $admin_id  = $request->Admin_id; // Fixed Variable Assignment
    $Vendor_id  = $request->Vendor_id; // Fixed Variable Assignment
    $email = $request->email;
    $mobile = $request->mobile;
    $name = $request->name;
    $created_by = $request->created_by;
    $permissionsArray = $request->input('permissions'); 
    $permissionsJson = json_encode($permissionsArray);
    $baseUrl = URL::to('/');
    $uid = $this->generateSecureRandomString(6);
    $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d h:i:s');
    
    
    $data  = DB::table('permissions')->get();
    $authuser = $request->session()->get('authuser');
    $auth_id = $authuser->role_id; 
  // dd($admin_id,$Vendor_id);
   
    if ($selected_role == 2) {
        $admin_id = null; // Fixed Assignment (`=` instead of `==`)
        $Vendor_id = null;
    } elseif ($selected_role == 3) {
        $Vendor_id = null;
        //$admin_id = $request->created_by;
    } elseif ($selected_role == 4) {
        $admin_id = null;
    }
    
    if($auth_id ==2){
       $admin_id = $request->created_by;
    }if($auth_id ==2 && $selected_role == 4){
       $admin_id = null;
    }if($auth_id == 3 && $selected_role == 4){
        $Vendor_id = $request->created_by;
    }
    
    //dd($Vendor_id, $created_by,$admin_id);
    
    $data = [
        'role_id' => $selected_role,
        'name' => $name,
        'email' => $email,
        'created_by' => $created_by,
        'admin_id' => $admin_id,
        'vendor_id' => $Vendor_id, // Fixed Variable Reference
        'u_id' => $uid,
        'mobile' => $mobile,
        'password' => $request->password, 
        'image' => $baseUrl . "/image/download.png",
        'status' => 1,
        'referral_code' => null,
        'referrer_id' => null,
        'permissions' => $permissionsJson,
        'wallet' => 0.00,
        'email' => $email
    ];
  
    $manager_key = 'FEGIScSYS3cMy';
    $apiUrl = 'https://api.gamebridge.co.in/seller/v1/get-newjilli-game-registration';
    $authorizationtoken = '1740119423505';
    
    $headers = [
        'Authorization' => 'Bearer ' . $manager_key,
        'Content-Type'  => 'application/json',
        'authorizationtoken' => 'Bearer ' . $authorizationtoken
    ];

    $requestData = json_encode(['mobile' => $mobile]);
    $payload = ['payload' => base64_encode($requestData)];

    // Jilli API Call
try {
    $response = Http::withHeaders($headers)->post($apiUrl, $payload);
    $apiResponse = json_decode($response->body());

    Log::info('Jilli API Response:', ['response' => $response->body()]);

    // ✅ Check if API response contains `accountNo`
    if ($response->successful() && isset($apiResponse->accountNo)) {
        $data['accountNo'] = $apiResponse->accountNo;
        $user = User::create($data);
        $userId = $user->id;
        return back()->with('success', 'Registration successful!');
    }

    // ❌ If accountNo is missing, return the error message
    $errorMessage = $apiResponse->msg ?? 'Registration failed. Please try again.';
    return back()->with('error', $errorMessage)->withInput();

} catch (\Exception $e){
    Log::error('API Error:', ['error' => $e->getMessage()]);
    return back()->with('error', 'Something went wrong: ' . $e->getMessage());
}
}


 public function allRoles(Request $request, $role = null) {
     $vendor = null;
     $admin = null;
     $data  = DB::table('permissions')->get();
     $authuser = $request->session()->get('authuser');
     $auth_role_id = $authuser->role_id; 
     $auth_id = $authuser->id;
 
 
    $permissions = DB::table('users')->where('id', $auth_id)->value('permissions');
    $permissionIds = json_decode($permissions, true);
        if (!is_array($permissionIds)) {
            $permissionIds = explode(',', $permissions);
        }
        $permission = DB::table('permissions')->whereIn('id', $permissionIds)->get();
      
     
     
     
    if (!$request->session()->has('authuser')) {
        return redirect()->route('login');
    }
    if ($role !== null) {
        $request->session()->put('selected_role', $role);
    }
    $role = $request->session()->get('selected_role', 3);
    
    $query = DB::table('users')->where('role_id', $role);

    if($auth_role_id ==2){
         $query = DB::table('users')->where('role_id', $role)->where('admin_id', $auth_id);
    }
    
    if ($request->has('mobile')) {
        $query->where('users.mobile', 'LIKE', '%' . $request->mobile . '%');
    }
     if ($request->has('u_id')) {
        $query->where('users.id', $request->u_id);
    }
     if ($request->has('admin_id')) {
        $query = DB::table('users')->where('admin_id', $request->admin_id);
        $admin = 2;
    }
     if ($request->has('vendor_id')){
        $query = DB::table('users')->where('vendor_id', $request->vendor_id);
        $vendor = 3;
    }
    $perPage = 10;
    $users = $query->paginate($perPage);
   // dd($permission);
    return view('roles.adminrole')
    ->with('users', $users)
    ->with('role', $role)
    ->with('auth_role_id', $auth_role_id)
    ->with('admin', $admin)
    ->with('permission', $permission)
    ->with('vendor', $vendor);

}

 
public function UpdatePermission(Request $request, $id){ 
      User::where('id', $id)->update([
        'permissions' => json_encode($request->permissions)
    ]);

    return back()->with('success', 'Permission Updated');
  }
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
}
