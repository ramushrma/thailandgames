@extends('admin.body.adminmaster')

@section('admin')
<div class="container-fluid mt-3">
    <div class="row">
<div class="col-md-12">
    <div class="white_shd full margin_bottom_30">
       <div class="full graph_head">
          <div class="">
             <h2>User Bet</h2>
          </div>
       </div>
       <div class="table_section padding_infor_info">
          <div class="table-responsive-sm">
            

             <table id="example" class="table table-striped" style="width:100%">
                <thead class="thead-dark">
                   <tr>
                      <th>Id</th>
                      <th>Amount</th>
                      <th>number</th>
					   <th>Game</th>
					    <th>GameNo</th>
                      <th>Date time</th>
                      
                      
                   </tr>
                </thead>
                <tbody>
                    @foreach($users as $row)
                    <tr>
                        <td>{{$row->id}}</td>
                        <td>{{$row->amount}}</td>
                        <td>{{$row->number}}</td>
					<td>
                        <?php 
                            $games = [
                                1 => "Wingo 1 Minute",
                                2 => "Wingo 3 Minute",
                                3 => "Wingo 5 Minute",
                                4 => "Wingo 10 Minute",
                                5 => "Aviator",
                                6 => "TRX 1 Minute",
                                7 => "TRX 3 Minute",
                                8 => "TRX 5 Minute",
                                9 => "TRX 10 Minute",
                                10 => "Dragon Tiger",
                                11 => "Plinko",
                                12 => "Mine Game",
                                13 => "Andar Bahar"
                            ];
                    
                            echo $games[$row->game_id] ?? "Unknown Game"; 
                        ?>
                    </td>
						<td>{{$row->games_no}}</td>
                        <td>{{$row->created_at}}</td>
                       
                    </tr>
                    @endforeach
                    </tbody>
                    
             </table>
          </div>
       </div>
    </div>
 </div>
</div>
</div> 
<div class="container-fluid">
    <div class="row">
<div class="col-md-12">
    <div class="white_shd full margin_bottom_30">
       <div class="full graph_head">
          <div class="heading1 margin_0 d-flex">
             <h2>User Withdrawal</h2>
          </div>
       </div>
       <div class="table_section padding_infor_info">
          <div class="table-responsive-sm">
            

             <table id="examplesss" class="table table-striped" style="width:100%">
                <thead class="thead-dark">
                   <tr>
                      <th>Id</th>
                      <th>Amount</th>
                       <th>Status</th>
                      <th>Date time</th>
                      
                      
                   </tr>
                </thead>
                <tbody>
                    @foreach($withdrawal as $rows)
                    <tr>
                        <td>{{$rows->id}}</td>
                        <td>{{$rows->amount}}</td>
                        @if($rows->status==1)  
                      <td>
                        
                       
                          <button class="dropbtn" style="font-size:13px;">Pending</button>
                      
                      </td>
                     @elseif($rows->status==2)
                     <td><button class="btn btn-success">Success</button></td>
                      @elseif($rows->status==3)
                     <td><button class="btn btn-danger">Reject</button></td>
                      @else
                      <td>
                     
                      </td> 
                      @endif
                        <td>{{$rows->created_at}}</td>
                       
                    </tr>
                    @endforeach
                    </tbody>
                    
             </table>
          </div>
       </div>
    </div>
 </div>
</div>
</div> 
<div class="container-fluid">
    <div class="row">
<div class="col-md-12">
    <div class="white_shd full margin_bottom_30">
       <div class="full graph_head">
          <div class="heading1 margin_0 d-flex">
             <h2>User Diposite</h2>
          </div>
       </div>
       <div class="table_section padding_infor_info">
          <div class="table-responsive-sm">
            

             <table id="exampless" class="table table-striped" style="width:100%">
                <thead class="thead-dark">
                   <tr>
                      <th>Id</th>
                      <th>Amount</th>
                      <!--<th>number</th>-->
					   <th>Transaction </th>
					   <th>Status</th>
					   
                      <th>Date time</th>
                      
                      
                   </tr>
                </thead>
                <tbody>
                    @foreach($dipositess as $rowtest)
                    <tr>
                        <td>{{$rowtest->id}}</td>
                        <td>{{$rowtest->cash}}</td>
						<td>{{$rowtest->order_id}}</td>
						  @if($rowtest->status==1)  
                     
                        
                       
                         <td><button class="btn btn-warning">Pending</button></td>
                      
                     
                     @elseif($rowtest->status==2)
                     <td><button class="btn btn-success">Success</button></td>
                      @elseif($rowtest->status==3)
                     <td><button class="btn btn-danger">Reject</button></td>
                      @else
                      <td>
                     
                      </td> 
                      @endif
                        <td>{{$rowtest->created_at}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    
             </table>
          </div>
       </div>
    </div>
 </div>
</div>
</div> 
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0 d-flex">
                        <h2>Wallet History</h2>
                    </div>
                </div>
                <div class="table_section padding_infor_info">
                    <div class="table-responsive-sm">
                        <table id="exampless" class="table table-striped" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                     <th>Action By</th>
                                    <th>Description 2</th>
                                    <th>Amount</th>
                                    <th>Type ID</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wallet_histories as $wallet)
                                    <tr>
                                        <td>{{ $wallet->id }}</td>
                                        <td>{{ $wallet->added_id  ?? 'N/A' }}</td>
                                        <td>{{ $wallet->description_2  ?? 'N/A' }}</td>
                                        <td>{{ $wallet->amount }}</td>
                                        <td>
                                            @if($wallet->type_id == 1) Aviator Salary 
                                            @elseif($wallet->type_id == 2) Payin 
                                            @elseif($wallet->type_id == 3) Withdraw 
                                            @elseif($wallet->type_id == 4) Aviator Cashout 
                                            @elseif($wallet->type_id == 5) Gift Claim 
                                            @elseif($wallet->type_id == 6) Bonus 
                                            @elseif($wallet->type_id == 7) Betting Rebate 
                                            @elseif($wallet->type_id == 8) Invitation Bonus 
                                            @elseif($wallet->type_id == 9) Turnover Commission 
                                            @elseif($wallet->type_id == 10) Daily Salary 
                                            @elseif($wallet->type_id == 11) Monthly Salary 
                                            @elseif($wallet->type_id == 12) Activity_reward_daily 
                                            @elseif($wallet->type_id == 13) Activity_reward_weekly 
                                            @elseif($wallet->type_id == 14) Attendance Bonus 
                                            @elseif($wallet->type_id == 15) Added by Admin 
                                            @elseif($wallet->type_id == 16) Deducted by Admin 
                                            @else Unknown 
                                            @endif
                                        </td>
                                        <td>{{ $wallet->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



 @endsection