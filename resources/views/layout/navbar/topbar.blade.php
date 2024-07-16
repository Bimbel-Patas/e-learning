 <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

     <!-- Sidebar Toggle (Topbar) -->
     <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
         <i class="fa fa-bars"></i>
     </button>


     <div class="">
         <div class="navbar-nav mt-3 d-none d-sm-block">

         </div>
     </div>

     <ul class="navbar-nav ">
         <li class="nav-item dropdown no-arrow mt-3 d-none d-sm-block">
             <p class="small">
                 @if (Auth()->user()->roles_id == 1)
                     <span class="badge p-2 badge-dark">Admin</span>
                 @elseif (Auth()->user()->roles_id == 2)
                     <span class="badge p-2 badge-danger">Pengajar</span>
                 @elseif (Auth()->user()->roles_id == 3)
                     <span class="badge p-2 badge-primary">Siswa</span>
                 @endif
                 Halo Selamat datang kembali, {{ Auth()->User()->name }}
             </p>
         </li>
     </ul>

     <!-- Topbar Navbar -->
     <ul class="navbar-nav ml-auto">
         <!-- Nav Item - Messages -->
         <li class="nav-item dropdown no-arrow">
             <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                 aria-haspopup="true" aria-expanded="false">
                 <i class="fa-solid fa-circle-chevron-down me-2"></i>
                 <span class="mr-2 text-gray-600 small">{{ Auth()->User()->name }}</span>
                 @if (Auth()->user()->gambar == null)
                     <img src="/asset/icons/profile-women.svg" class="img-profile rounded-circle me-2" alt="">
                 @else
                     <img class="img-profile rounded-circle me-2"
                         src="{{ asset('storage/user-images/' . Auth()->user()->gambar) }}">
                 @endif
             </a>
             <!-- Dropdown - User Information -->
             <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                 @if (Auth()->user()->roles_id == 1 || Auth()->user()->roles_id == 2)
                     <a class="dropdown-item"
                         href="{{ route('viewProfilePengajar', ['token' => encrypt(Auth()->User()->id)]) }}">
                         <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                         Profile
                     </a>
                 @endif
                 @if (Auth()->user()->roles_id == 3)
                     <a class="dropdown-item"
                         href="{{ route('viewProfileSiswa', ['token' => encrypt(Auth()->User()->id)]) }}">
                         <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                         Profile
                     </a>
                 @endif
                 <div class="dropdown-divider"></div>
                 <a class="dropdown-item" href="#" aria-expanded="false" data-toggle="modal"
                     data-target="#logoutModal">
                     <span class=" text-danger"><i class="fa-solid mr-2 fa-right-from-bracket"></i>
                         Logout</span>
                 </a>
             </div>
         </li>

     </ul>

 </nav>
