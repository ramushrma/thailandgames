@extends('admin.body.adminmaster')

@section('admin')
@if(in_array("1", $permissions))
<div class="midde_cont">
    <div class="container-fluid">
        <div class="row column_title">
            <div class="col-md-12">
                <div class="page_title">
                    <h2>Dashboard</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <form action="{{route('dashboard')}}" method="get">
                    @csrf
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="end_date">End Date:</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
            </div>
            <div class="col-md-2 mt-4">
                <button type="submit" class="btn btn-success">Search</button>
                <a href="{{route('dashboard')}}" class="btn btn-secondary">Reset</a>
            </div>
                </form>
        </div>
        <div class="row column1 mt-4">
            @if($auth_role!=2 && $auth_role!=3)
            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-user yellow_color"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">{{$users[0]->totaladmin ?? 0}}</p>
                        <p class="head_couter">Total Admin</p>
                    </div>
                </div>
            </div>
            @endif

            @if($auth_role!=3)
            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-gift  purple_color2"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">{{$users[0]->totalvendor ?? 0}}</p>
                        <p class="head_couter">Total Vendor</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-users blue1_color"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">{{$users[0]->totaluser ?? 0}}</p>
                        <p class="head_couter">Total Player</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-clock-o blue1_color"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">{{$users[0]->activeuser ?? 0}}</p>
                        <p class="head_couter">Active </p>
                    </div>
                </div>
            </div>
            @if(in_array("10", $permissions))
            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-cloud-download green_color"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">₹ {{$users[0]->totaldeposit ?? 0}}</p>
                        <p class="head_couter">Total Deposit</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-cloud-download green_color"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">₹ {{$users[0]->tdeposit ?? 0}}</p>
                        <p class="head_couter">Today Deposit</p>
                    </div>
                </div>
            </div>
            @endif
            @if(in_array("11", $permissions))
            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-comments-o red_color"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">₹ {{$users[0]->totalwithdraw ?? 0}}</p>
                        <p class="head_couter">Total Withdrawal</p>
                    </div>
                </div>
            </div>
            @endif
            @if(in_array("14", $permissions))
            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-comments yellow_color"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">{{$users[0]->totalfeedback ?? 0}}</p>
                        <p class="head_couter">Feedback</p>
                    </div>
                </div>
            </div>
             @endif
            <div class="col-md-6 col-lg-3">
                <div class="full counter_section margin_bottom_30">
                    <div class="couter_icon">
                        <div><i class="fa fa-gamepad purple_color"></i></div>
                    </div>
                    <div class="counter_no">
                        <p class="total_no">{{$users[0]->totalgames ?? 0}}</p>
                        <p class="head_couter">Total Games</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
