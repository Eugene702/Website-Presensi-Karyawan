<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<nav class="bg-white w-96">


    <div class="container mx-auto p-4">
        <div class="bg-white  overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center">

                <button onclick="my_modal_1.showModal()">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://via.placeholder.com/60' }}"
                        alt="Avatar" class="rounded-full w-16 h-16">

                </button>
                <dialog id="my_modal_1" class="modal bg-gray-500 bg-opacity-50 backdrop-blur-md ">
                    <div class="modal-box bg-slate-200 p-6 rounded-lg shadow-lg max-w-md w-full">
                        <h2 class="text-2xl font-bold mb-4 text-black">Upload Foto Anda</h2>
                        <p class="mb-4 text-black">Silakan unggah foto profil Anda.</p>
                        <form method="POST" action="{{ route('user.uploadProfilePicture') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="profile_picture" class="file-input w-full max-w-xs mb-4" />
                            <div class="modal-action flex justify-between">
                                <button type="submit" class="btn btn-primary bg-red-500 border-none hover:bg-slate-100">Upload</button>
                                <button class="btn btn-secondary bg-green-600 border-none hover:bg-slate-100" type="button" onclick="my_modal_1.close()">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>


                <div class="ml-4">
                    <h3 class="text-xl font-semibold">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-500">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    @csrf
                    <button type="submit" class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>

            <div class="mt-8">
                <h4 class="text-xl font-semibold">Presensi</h4>
                <p class="text-gray-500">Lakukan Check in dan Check out untuk melengkapi daftar hadir harian anda</p>
                <p class="text-gray-700 mt-4">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</p>
                <p class="text-red-600 text-2xl font-bold mt-2">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s') }}</p>

                @if(session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
                @endif


                <div class="flex justify-between items-center mt-8">
                    <form method="Get" action="{{ route('presensi.clockIn') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white py-2 px-6 rounded-lg flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m0 0h13m-5 10H3m5 4v-4m0 0H3" />
                            </svg>
                            <span>Check-In</span>
                        </button>
                    </form>

                    <form method="get" action="{{ route('presensi.clockOut') }}">
                        @csrf
                        <button type="submit" class="bg-gray-300 text-gray-500 py-2 px-6 rounded-lg flex items-center space-x-2 ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m0 0h13m-5 10H3m5 4v-4m0 0H3" />
                            </svg>
                            <span>Check-Out</span>
                        </button>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <p>Tidak Hadir? <a href="/presensi/izin" class="text-red-600"> Klik Disini</a></p>
                </div>
            </div>
        </div>
    </div>





</nav>