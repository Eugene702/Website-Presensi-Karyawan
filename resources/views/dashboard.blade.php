<x-app-layout>
    <div class="container mx-auto flex flex-col lg:flex-row space-y-8 lg:space-y-0 lg:space-x-8 text-gray-950">
        <!-- Panel Kiri: Profil Pengguna dan Presensi -->
        <div class="w-full lg:w-1/3 bg-white p-6 rounded-lg shadow-md">
            <div class="text-center">
                <button onclick="my_modal_1.showModal()">
                    <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : 'https://via.placeholder.com/60' }}"
                        alt="Avatar" class="rounded-full w-16 h-16">
                </button>
                <dialog id="my_modal_1" class="modal bg-gray-500 bg-opacity-50 backdrop-blur-md">
                    <div class="modal-box bg-slate-200 p-6 rounded-lg shadow-lg max-w-md w-full">
                        <h2 class="text-2xl font-bold mb-4 text-black">Upload Foto Anda</h2>
                        <p class="mb-4 text-black">Silakan unggah foto profil Anda.</p>
                        <form method="POST" action="{{ route('user.uploadProfilePicture') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="profile_picture" class="file-input w-full max-w-xs mb-4" />
                            <div class="modal-action flex justify-between">
                                <button type="submit" class="btn btn-primary bg-red-500 border-none hover:bg-slate-100 text-white">Upload</button>
                                <button class="btn btn-secondary bg-green-600 border-none hover:bg-slate-100 text-white" type="button" onclick="my_modal_1.close()">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>

                <h3 class="text-xl font-semibold mt-4">{{ Auth::user()->name }}</h3>
                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="border-gray-300 border-[1px] text-gray-950 py-2 px-4 rounded hover:bg-gray-300 w-full text-xl font-bold ">
                        Logout
                    </button>
                </form>
            </div>

            <div class="mt-8 bg-gray-100 p-4 rounded-lg">
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

                <h4 class="text-xl font-semibold mb-2">Presensi</h4>
                <p class="">Lakukan Check in dan Check out untuk melengkapi daftar hadir harian anda</p>

                <p class="text-gray-700 mt-4 text-center">{{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y') }}</p>
                <p class="bg-custom-gradient text-2xl font-bold mt-2 text-center">
                    {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat(' H:i:s') }}
                </p>



                <div class="flex  sm:flex-row  mt-6  sm:space-y-0 sm:space-x-4 flex-row justify-between ">
                    <!-- Tombol Check-In -->
                    <form method="GET" action="{{ route('presensi.clockIn') }}" class="">
                        @csrf
                        <button type="submit"
                            class="{{ $hasClockedIn ? 'bg-gray-300 text-gray-500' : 'bg-red-500 text-white' }} py-1 sm:py-2 px-4 sm:px-6 rounded-lg flex items-center space-x-2  "
                            {{ $hasClockedIn ? 'disabled' : '' }}>
                            <i data-feather="log-in"></i>
                            <div class="flex flex-col">
                                <p class="text-sm sm:text-base">Check-In</p>
                                <p class="text-xs sm:text-sm">{{ $todayPresensi && $todayPresensi->clock_in ? $todayPresensi->clock_in->format('H:i:s') : '-' }}</p>
                            </div>
                        </button>
                    </form>

                    <!-- Tombol Check-Out -->
                    <form method="GET" action="{{ route('presensi.clockOut') }}" class="">
                        @csrf
                        <button type="submit"
                            class="{{ $hasClockedOut || !$hasClockedIn ? 'bg-gray-300 text-gray-500' : 'bg-red-500 text-white' }} py-1 sm:py-2 px-4 sm:px-6 rounded-lg flex items-center space-x-2"
                            {{ $hasClockedOut || !$hasClockedIn ? 'disabled' : '' }}>
                            <i data-feather="log-out"></i>
                            <div class="flex flex-col">
                                <p class="text-sm sm:text-base">Check-Out</p>
                                <p class="text-xs sm:text-sm">{{ $todayPresensi && $todayPresensi->clock_out ? $todayPresensi->clock_out->format('H:i:s') : '-' }}</p>
                            </div>
                        </button>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <p>Tidak Hadir? <button onclick="my_modal_4.showModal()"> <span class="bg-custom-gradient font-semibold">Klik Disini</span></button></p>
                </div>
                <dialog id="my_modal_4" class="modal bg-gray-500 bg-opacity-50 backdrop-blur-md">
                    <div class="modal-box w-11/12 max-w-5xl bg-white">
                        <h2 class="text-2xl font-bold mb-4">Lapor Presensi Forum Human Capital Indonesia</h2>
                        <form method="POST" action="{{ route('presensi.izinSubmit') }}">
                            @csrf
                            <div class="mb-4">
                                <p class="my-5">Form ini digunakan untuk melaporkan kehadiran tanpa check in</p>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <select name="keterangan" id="keterangan" class="mt-1 block w-full">
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                                <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full"></textarea>
                            </div>
                            <div class="flex justify-between">
                                <button class="btn px-4 py-2 rounded bg-gradient-to-r from-pink-300 to-red-300 border-none text-red-500 font-bold" type="button" onclick="my_modal_4.close()">Batal</button>
                                <button type="submit" class="bg-red-500 btn text-white px-4 py-2 rounded border-none hover:bg-pink-300">Submit</button>
                            </div>
                        </form>
                    </div>
                </dialog>
            </div>
        </div>


        <!-- Panel Kanan: Riwayat Presensi -->
        <div class="w-full lg:w-2/3 bg-white p-6 rounded-lg shadow-md hidden md:block">
            <h2 class="text-2xl font-bold mb-4">Riwayat Presensi</h2>
            <table class="table-auto w-full text-left">
                <thead>
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Waktu Check In</th>
                        <th class="px-4 py-2">Waktu Check Out</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($presensi as $index => $item)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2">{{ $item->clock_in ? $item->clock_in->format('H:i:s') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->clock_out ? $item->clock_out->format('H:i:s') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->status }}</td>
                        <td class="px-4 py-2">{{ $item->catatan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="w-full lg:w-2/3 bg-white p-6 rounded-lg shadow-md  md:hidden">
            <h2 class="text-2xl font-bold mb-4">Riwayat Presensi</h2>
            <table class="table-auto w-full text-left">
                <thead>
                    <tr>
                        <th class=" py-2">No</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Waktu </th>
                        
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($presensi as $index => $item)
                    <tr class="border-b">
                        <td class=" py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2"> <span class="btn btn-sm bg-green-400 text-gray-950 my-1"> {{ $item->clock_in ? $item->clock_in->format('H:i:s') : '-' }} </span>
                        <span class="btn btn-sm  bg-button text-white" >{{ $item->clock_out ? $item->clock_out->format('H:i:s') : '-' }}</span></td>
                        <td class="px-4 py-2">{{ $item->status }}</td>
                        <td class="px-4 py-2">{{ $item->catatan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>



    </div>
</x-app-layout>