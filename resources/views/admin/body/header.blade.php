 <?php if(Session::has('id')){

}else{
	
	header("Location: https://root.thargames.live/");
            die; 

} 
      
?>
<div id="content">
    <!-- topbar -->
    <div class="topbar">
       <nav class="navbar navbar-expand-lg navbar-light">
          <div class="full">
             <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i class="fa fa-bars"></i></button>
             <div class="logo_section">
                <h3 class="img-responsive text-white mt-3 ml-2" style="color:red;">𝕏𝕘𝕒𝕞𝕓𝕝𝕦𝕣 𝔾𝕒𝕞𝕖</h3>
                {{-- <a href="index.html"><img class="img-responsive" src="images/logo/logo.png" alt="#" /></a> --}}
             </div>
             <div class="right_topbar">
                <div class="icon_info">
                   {{-- <ul>
                      <li><a href="#"><i class="fa fa-bell-o"></i><span class="badge">2</span></a></li>
                      <li><a href="#"><i class="fa fa-question-circle"></i></a></li>
                      <li><a href="#"><i class="fa fa-envelope-o"></i><span class="badge">3</span></a></li>
                   </ul> --}}
                   <ul class="user_profile_dd">
                      <li>
                         <a class="dropdown-toggle" data-toggle="dropdown">
                             <!--<img class="img-responsive rounded-circle" src="https://root.jupitergames.app/uploads/jupiter_logo.png" alt="#" />-->
                             <span class="name_user">Admin</span></a>
                         <div class="dropdown-menu">
                            <!--<a class="dropdown-item" href="#">My Profile</a>-->
                            {{-- <a class="dropdown-item" href="settings.html">Settings</a>
                            <a class="dropdown-item" href="help.html">Help</a> --}}
                            <a class="dropdown-item" href="{{route('auth.logout')}}"><span>Log Out</span> <i class="fa fa-sign-out"></i></a>
                         </div>
                      </li>
                   </ul>
                </div>
             </div>
          </div>
       </nav>
    </div>
    <!-- end topbar -->


 