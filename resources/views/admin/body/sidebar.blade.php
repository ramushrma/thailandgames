<div class="full_container">
         <div class="inner_container">
            <!-- Sidebar  -->
            <nav id="sidebar">
               <div class="sidebar_blog_1">
                  <div class="sidebar-header">
                     <div class="logo_section">
                        <a href="index.html"><img class="logo_icon img-responsive" src="images/logo/logo_icon.png" alt="#" /></a>
                     </div>
                  </div>
                  <div class="sidebar_user_info">
                     <div class="icon_setting"></div>
                     <div class="user_profle_side">
                        <div class="user_img">
                            <!--<img class="img-responsive" src="https://root.jupitergames.app/uploads/jupiter_logo.png" style="height:50px; width:100px;" alt="#" />-->
                            <img class="img-responsive" src="https://root.fomoplay.club/uploads/fomoplay.png" style="height:50px; width:100px; margin-top:15px;" alt="#" />
                            </div>
                        <div class="user_info">
                             @php
                                $authuser = session()->get('authuser'); // Session se auth user le rahe hain
                                $auth = $authuser->role_id ?? null;
                                $auth_id = $authuser->id ?? null;
                                $auth_name = $authuser->name ?? online;
                                $permissions = DB::table('users')->where('id', $auth_id)->value('permissions');
                                $permissions = json_decode($permissions, true);
                            @endphp
                          <h6>{{ $auth == 1 ? 'Super Admin' : ($auth == 2 ? 'Admin' : 'Vendor') }}</h6>
                           <p><span class="online_animation"></span> {{ $auth_name}} </p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="sidebar_blog_2">
                  <h4>General</h4>
                  <ul class="list-unstyled components">
                     @if(in_array("1", $permissions))
                       <li><a href="{{route('dashboard')}}"><i class="fa fa-dashboard yellow_color"></i> <span>Dashboard</span></a></li>
                    @endif
                     
                     <!--<li><a href="{{route('attendance.index')}}"><i class="fa fa-clock-o purple_color2"></i> <span>Attendance</span></a></li>-->
                 
                   <li><a href="{{route('users')}}"><i class="fa fa-users orange_color"></i> <span>Players</span></a></li>
                  <li><a href="{{route('block.user.list')}}"><i class="fa fa-ban red_color"></i> <span>Block Player List</span></a></li>

                        @if($auth == 1)
                        <!-- Agar auth = 1 hai to sab dikhayega -->
                       <li><a href="{{ route('allroles', 2) }}"><i class="fa fa-user-secret red_color"></i> <span>Admin</span></a></li>
                       <li><a href="{{ route('allroles', 3) }}"><i class="fa fa-briefcase green_color"></i> <span>Vendor</span></a></li>

                        @elseif($auth == 2)
                            <li><a href="{{ route('allroles', 3) }}"><i class="fa fa-briefcase green_color"></i> <span>Vendor</span></a></li>
                        @endif
                     @php
                         $colourpredictions = DB::select("SELECT * FROM `game_settings` LIMIT 4;");
                     @endphp
                     
                    @if(in_array("2", $permissions))
                       <li>
                        <a href="#Colour_prediction" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <i class="fa fa-paint-brush blue2_color"></i> <span>Colour Prediction</span></a>
                        <ul class="collapse list-unstyled" id="Colour_prediction">
                            @foreach($colourpredictions as $item)
                            <li><a href="{{route('colour_prediction',$item->id)}}"> <span>{{$item->name}}</span></a></li>
                            @endforeach
                        </ul>
                    </li>

                    @endif
                    
                    
                      @if(in_array("3", $permissions))
                         @includeIf('admin.body.aviator_sidebar')
                     @endif
                     
                       
                      @if(in_array("4", $permissions))
                       	@includeIf('admin.body.andarbahar_sidebar')
                      @endif
                    
                    <!--@includeIf('admin.body.trx_sidebar')-->
                    <!--@includeIf('admin.body.dragon_sidebar')-->
			     
					  <!--<li><a href="{{route('plinko')}}"><i class="fa fa-gamepad purple_color2"></i> <span>Plinko</span></a></li>-->
					  
					   @php
                         $game_id = DB::select("SELECT * FROM `game_settings` WHERE `id` IN (1, 2, 3, 4, 5, 13);");
                       @endphp
					  
					   @if(in_array("5", $permissions))
                      <li>
                        <a href="#apps-xy" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <i class="fa fa-history blue2_color"></i> <span>Bet History</span></a>
                        <ul class="collapse list-unstyled" id="apps-xy">
                            @foreach($game_id as $itemm)
                            <li><a href="{{route('all_bet_history',$itemm->id)}}"> <span>{{$itemm->name}}</span></a></li>
                            @endforeach
                        </ul>
                    </li>

                      @endif
                    
					
					  
					    <!--<li><a href="{{route('first.deposit.bonus')}}"><i class="fa fa-list red_color"></i> <span>First Deposit Bonus</span></a></li>-->
        					 @if(in_array("6", $permissions))
                            <li><a href="{{route('gift')}}"><i class="fa fa-gift purple_color2"></i> <span>Gift</span></a></li>
                            @endif
                            
                            @if(in_array("7", $permissions))
                            <li><a href="{{route('giftredeemed')}}"><i class="fa fa-history purple_color2"></i> <span>Gift Redeemed History</span></a></li>
                            @endif
                            
                            @if(in_array("8", $permissions))
                            <li><a href="{{route('banner')}}"><i class="fa fa-bullhorn blue_color"></i> <span>Activity & Banner</span></a></li>
                            @endif

       
          @if(in_array("9", $permissions))
         <li><a href="{{route('admin.usdtqr')}}"><i class="fa fa-qrcode yellow_color"></i> <span> QR & USDT Address</span></a></li>
           @endif
         
         
          @if(in_array("10", $permissions))
           <li>
            <a href="#app20" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
            <i class="fa fa-credit-card green_color"></i><span>USDT Payin</span></a>
                <ul class="collapse list-unstyled" id="app20">
                    <li><a href="{{ route('usdt_deposit', 1) }}">Pending</a></li>
                    <li><a href="{{ route('usdt_deposit', 2) }}">Success</a></li>
                    <li><a href="{{ route('usdt_deposit',3) }}">Reject</a></li>
                </ul>
            </li>
           @endif   
       
        
         @if(in_array("11", $permissions))
        <li>
            <a href="#app21" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="fa fa-money green_color"></i> <span>USDT Withdrawal</span></a>
                <ul class="collapse list-unstyled" id="app21">
                    <li><a href="{{ route('usdt_widthdrawl', 1) }}">Pending</a></li>
                    <li><a href="{{ route('usdt_widthdrawl', 2) }}">Success</a></li>
                    <li><a href="{{ route('usdt_widthdrawl',3) }}">Reject</a></li>
                    <li><a href="{{route('withdrawal_charges')}}"><b>Withdrawal Charges</b></a></li>
               </ul>
        </li>
           @endif
  
  
             @if(in_array("12", $permissions))
            <li><a href="{{route('support_setting')}}"><i class="fa fa-headphones purple_color2"></i> <span>Customer Support Setting </span></a></li>
            @endif
            
            @if(in_array("13", $permissions))
            <li><a href="{{route('live_scroll_notification')}}"><i class="fa fa-bullhorn purple_color2"></i> <span>Announcement & Ref Bonus</span></a></li>
            @endif
            
            @if(in_array("14", $permissions))
            <li><a href="{{route('Appsnotifications')}}"><i class="fa fa-bell purple_color2"></i> <span>App Notification</span></a></li>
            @endif
            
             @if(in_array("16", $permissions))
            <li><a href="{{route('findfeedback')}}"><i class="fa fa-comments yellow_color"></i> <span>Findfeedback</span></a></li>
            @endif

	
		
				<li><a href="{{route('create_role')}}"><i class="fa fa-user-plus blue_color"></i> <span>
				  @if($auth == 3)
                    Create User
                @else
                    Create Role
                @endif
				    </span></a></li>
		<!--<li><a href="{{route('businessSetting.index')}}"><i class="fa fa-warning red_color"></i> <span>Business Setting</span></a></li>-->
        <li><a href="{{route('change_password')}}"><i class="fa fa-unlock red_color"></i> <span>Change Password</span></a></li>
        <li><a href="{{route('auth.logout')}}"><i class="fa fa-lock yellow_color"></i> <span>Logout</span></a></li>
                    
                     {{-- <li>
                        <a href="contact.html">
                        <i class="fa fa-paper-plane red_color"></i> <span>Contact</span></a>
                     </li>
                     <li class="active">
                        <a href="#additional_page" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-clone yellow_color"></i> <span>Additional Pages</span></a>
                        <ul class="collapse list-unstyled" id="additional_page">
                           <li>
                              <a href="profile.html">> <span>Profile</span></a>
                           </li>
                           <li>
                              <a href="project.html">> <span>Projects</span></a>
                           </li>
                           <li>
                              <a href="login.html">> <span>Login</span></a>
                           </li>
                           <li>
                              <a href="404_error.html">> <span>404 Error</span></a>
                           </li>
                        </ul>
                     </li>
                     <li><a href="map.html"><i class="fa fa-map purple_color2"></i> <span>Map</span></a></li>
                     <li><a href="charts.html"><i class="fa fa-bar-chart-o green_color"></i> <span>Charts</span></a></li>
                     
                     <li><a href="settings.html"><i class="fa fa-cog yellow_color"></i> <span>Settings</span></a></li> --}}
                  </ul>
               </div>
            </nav>

            <!-- end sidebar -->