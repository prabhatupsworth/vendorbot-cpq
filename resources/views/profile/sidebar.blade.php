 <div class="col-xl-3 col-lg-12 theiaStickySidebar">

     <!-- Settings Sidebar -->
     <div class="card">
         <div class="card-body">
             <div class="settings-sidebar">
                 <h4 class="fw-semibold mb-3">General Settings</h4>
                 <div class="list-group list-group-flush settings-sidebar">
                     <a href="{{ route('profile') }}" class="fw-medium {{ request()->routeIs('profile') ? 'active' : '' }}">Profile</a>
                     <a href="{{ route('security') }}" class="fw-medium {{ request()->routeIs('security') ? 'active' : '' }}">Security</a>
                 </div>
             </div>
         </div>
     </div>
     <!-- /Settings Sidebar -->

 </div>
