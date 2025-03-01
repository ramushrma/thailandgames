@extends('admin.body.adminmaster')
@section('admin')
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                      <form class="form-horizontal" method="POST" action="{{ route('createrole') }}">
                            @csrf
                                <div class="card-body">
                                    <div class="text-center">
                                        <h4 class="card-title">Personal Info</h4>
                                    </div>
                                     <div class="form-group row">
                                        <label for="fname" class="col-sm-4 text-center control-label col-form-label">Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="name" class="form-control" id="fname" placeholder="Enter name"
                                            value="{{ old('name') }}">
                                        @error('name')
                                        <div  style="color: red;">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    </div>
                                      <div class="form-group row">
                                        <label for="cono1" class="col-sm-4 text-center control-label col-form-label">Mobile No</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="mobile" class="form-control" id="cono1"
                                               value="{{ old('mobile') }}" placeholder="Contact No Here">
                                        @error('mobile')
                                        <div  style="color: red;">{{ $message }}</div>
                                        @enderror
                                        </div>
                                        
                                    </div>
                                    <div class="form-group row">
                                        <label for="fname" class="col-sm-4 text-center control-label col-form-label">Enter Email</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="email" class="form-control" id="fname"
                                              value="{{ old('email') }}" placeholder="Enter Email">
                                        @error('email')
                                        <div  style="color: red;">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lname" class="col-sm-4 text-center control-label col-form-label">Password</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="password" class="form-control" id="lname"
                                               value="{{ old('password') }}" placeholder="Password Here">
                                        @error('password')
                                        <div  style="color: red;">{{ $message }}</div>
                                        @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lname" class="col-sm-4 text-center control-label col-form-label">Confirm Password</label>
                                        <div class="col-sm-8">
                                            <input type="text"  name="confirm_password" class="form-control" id="lname"
                                               value="{{ old('confirm_password') }}" placeholder="Confirm Password Here">
                                            <input type="hidden" name="created_by" value="{{$id}}">
                                            @error('confirm_password')
                                            <div  style="color: red;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                      <div class="form-group row">
                                        <label for="lname" class="col-sm-4 text-center control-label col-form-label">Select Role</label>
                                        <div class="col-sm-8">
                                             <label for="adminDropdown" class="form-label"></label>
                                                <label for="adminDropdown" class="form-label"></label>
                                                <select class="form-control" id="adminDropdown" name="selected_role">
                                                    <option value="">Select Role</option>
                                                    @if($auth == 1)
                                                        <option value="2" {{ old('selected_role') == "2" ? 'selected' : '' }}>Admin</option>
                                                        <option value="3" {{ old('selected_role') == "3" ? 'selected' : '' }}>Vendor</option>
                                                        <option value="4" {{ old('selected_role') == "4" ? 'selected' : '' }}>User</option>
                                                    @elseif($auth == 2)
                                                        <option value="3" {{ old('selected_role') == "3" ? 'selected' : '' }}>Vendor</option>
                                                        <option value="4" {{ old('selected_role') == "4" ? 'selected' : '' }}>User</option>
                                                    @else
                                                        <option value="4" {{ old('selected_role') == "4" ? 'selected' : '' }}>User</option>
                                                    @endif
                                                </select>
                                                @error('selected_role')
                                                <div  style="color: red;">{{ $message }}</div>
                                                @enderror
                                        </div>
                                    </div>
                                </div>
                            <!--    <div class="border-top">-->
                            <!--        <div class="card-body">-->
                            <!--            <button type="button" class="btn btn-primary">Submit</button>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</form>-->
                    </div>
                      <div class="col-md-6">
                          @if($auth !== 3)
                            <h5 class="card-title">Permission</h5>
                            <div class="row">
                                @foreach($data->chunk(3) as $chunk) 
                                    <div class="row mb-2">
                                        @foreach($chunk as $permission)
                                          <div class="col-md-4">
                                            <div class="form-check">
                                                <input type="checkbox" 
                                                       class="form-check-input" 
                                                       name="permissions[]" 
                                                       id="perm_{{ $permission->id }}" 
                                                       value="{{ $permission->id }}" 
                                                       {{ is_array(old('permissions')) && in_array($permission->id, old('permissions')) ? 'checked' : '' }}>
                                        
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endforeach
                               <div class="">
                                   <hr>
                                   <div class="row">
                                        <!-- Admin Dropdown -->
                                     @if($auth == 1)
                                        <div class="col-md-6">
                                          <label for="adminDropdown" class="form-label">Inside Admin</label>
                                          <select class="form-control select2" id="roleAdminDropdown" name="Admin_id">
                                            <option value="">Select Admin Option</option>
                                        </select>
                                        </div>
                                        @endif
                                        <!-- Vendor Dropdown -->
                                        <div class="col-md-6">
                                            <label for="vendorDropdown" class="form-label">Inside Vendor</label>
                                          <select class="form-control select2" id="roleVendorDropdown" name="Vendor_id">
                                            <option value="">Select Vendor Option</option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                           @endif
                      </div>
                         <div class="border-top">
                            <div class="card-body text-center">
                                <button type="submit" class="btn btn-primary">Submit</button> 
                            </div>
                        </div>
                        </form> <!-- Ensure this closing tag exists -->

                </div>
            </div>
        </div>
    </div>
</div>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
    var $s2 = jQuery.noConflict(); // Select2 uses $s2 instead of $
    $s2(document).ready(function () {
        var auth = <?php echo json_encode($auth); ?>; // PHP se auth value le rahe hain

        // Select2 Initialize
        $s2('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        // Fetch Initial Admin and Vendor Data
        $s2.ajax({
            url: "/get-dependent-roles",
            type: "GET",
            dataType: "json",
            success: function (response) {
                console.log("Response Received:", response);

                // Admin Dropdown Populate
                $s2("#roleAdminDropdown").empty().append('<option value="">Select Admin Option</option>');
                $s2.each(response.admin, function (index, role) {
                    $s2("#roleAdminDropdown").append('<option value="' + role.id + '">' + role.name + '</option>');
                });

                // Vendor Dropdown (Initially Empty)
                $s2("#roleVendorDropdown").empty().append('<option value="">Select Vendor Option</option>');
                
                // Agar $auth == 1 nahi hai, to Vendor list ko bhi default fill kar do
                if (auth != 1) {
                    $s2.each(response.vendor, function (index, role) {
                        $s2("#roleVendorDropdown").append('<option value="' + role.id + '">' + role.name + '</option>');
                    });
                }
            },
            error: function (xhr) {
                console.log("Error:", xhr.status, xhr.responseText);
                alert("Error fetching roles!");
            }
        });

        // Admin Dropdown Change Event (Only if auth == 1)
        if (auth == 1) {
            $s2("#roleAdminDropdown").change(function () {
                var selectedAdminId = $s2(this).val();
                
                if (selectedAdminId) {
                    console.log("Selected Admin ID:", selectedAdminId);

                    // Fetch Vendors Based on Selected Admin ID
                    $s2.ajax({
                        url: "/get-dependent-roles",
                        type: "GET",
                        data: { admin_id: selectedAdminId },
                        dataType: "json",
                        success: function (response) {
                            console.log("Vendor Response:", response);

                            // Populate Vendor Dropdown
                            $s2("#roleVendorDropdown").empty().append('<option value="">Select Vendor Option</option>');
                            $s2.each(response.vendor, function (index, role) {
                                $s2("#roleVendorDropdown").append('<option value="' + role.id + '">' + role.name + '</option>');
                            });
                        },
                        error: function (xhr) {
                            console.log("Error:", xhr.status, xhr.responseText);
                            alert("Error fetching vendors!");
                        }
                    });
                } else {
                    // If No Admin Selected, Reset Vendor Dropdown
                    $s2("#roleVendorDropdown").empty().append('<option value="">Select Vendor Option</option>');
                }
            });
        }
    });
</script>

<script>
    var $s2 = jQuery.noConflict(); // Agar multiple jQuery ho to conflict avoid kare
    $s2(document).ready(function () {
        // Select2 Activation
        $s2('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
        // Role Dropdown Change Event
        $s2("#adminDropdown").change(function () {
            var selectedRole = $s2(this).val(); // Selected Role ID
            if (selectedRole == "2") { 
                // Admin Select Kare to dono disable ho jayenge
                $s2("#roleAdminDropdown, #roleVendorDropdown").prop("disabled", true);
            } else if (selectedRole == "3") { 
                // Vendor select kare to sirf Inside Vendor disable hoga
                $s2("#roleAdminDropdown").prop("disabled", false);
                $s2("#roleVendorDropdown").prop("disabled", true);
            } else {
                // User select kare to dono active rahenge
                $s2("#roleAdminDropdown, #roleVendorDropdown").prop("disabled", false);
            }
        });
    });
</script>
<script>
    var $s2 = jQuery.noConflict(); // Conflict avoid karega

$s2(document).ready(function () {
    // Select2 Activation
    $s2('.select2').select2({
        placeholder: "Select an option",
        allowClear: true
    });

    // Role Dropdown Change Event
    $s2("#adminDropdown").on("change", function () {
        var selectedRole = $s2(this).val(); // Selected Role ID
        
        if (selectedRole == "4") {
            $s2("input[name='permissions[]']").prop("disabled", true); // Disable Permission
        } else {
            $s2("input[name='permissions[]']").prop("disabled", false); // Enable Permission
        }
    });

    // Page Refresh pe bhi role check hoke disable rahe
    $s2("#adminDropdown").trigger("change");
});

</script>
@endsection
