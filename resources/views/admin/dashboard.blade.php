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
                        <form method="POST" action="{{ route('user.uploadProfilePicture') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="profile_picture" class="file-input w-full max-w-xs mb-4" />
                            <div class="modal-action flex justify-between">
                                <button type="submit"
                                    class="btn btn-primary bg-red-500 border-none hover:bg-slate-100 text-white">Upload</button>
                                <button class="btn btn-secondary bg-green-600 border-none hover:bg-slate-100 text-white"
                                    type="button" onclick="my_modal_1.close()">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>

                <h3 class="text-xl font-semibold mt-4">{{ Auth::user()->name }}</h3>
                <p class="text-gray-500">{{ Auth::user()->email }}</p>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="border-gray-300 border-[1px] text-gray-950 py-2 px-4 rounded hover:bg-gray-300 w-full text-xl font-bold ">
                        Logout
                    </button>
                </form>
            </div>


        </div>


        <!-- Panel Kanan: Riwayat Presensi -->
        <div x-data="{ isImageModalOpen: false, modalImageUrl: '' }"
            class="w-full lg:w-2/3 bg-white p-6 rounded-lg shadow-md hidden md:block">

            {{-- Letakkan kode ini setelah </table>, tapi sebelum </div> penutup utama --}}
        <div x-show="isImageModalOpen" @keydown.escape.window="isImageModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 transition-opacity"
            style="display: none;">

            <div @click.away="isImageModalOpen = false"
                class="relative bg-white p-4 rounded-lg shadow-lg max-w-3xl max-h-[90vh]">

                <button @click="isImageModalOpen = false"
                    class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-xl font-bold z-10">&times;</button>

                <img :src="modalImageUrl" alt="Tampilan Penuh" class="object-contain max-w-full max-h-[85vh]">
            </div>
        </div>

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Riwayat Presensi</h2>
            {{-- TOMBOL EXPORT PDF --}}
            <div class="flex items-center space-x-4">
                <!-- FORM FILTER TANGGAL -->
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center space-x-2">
                    <input type="date" name="filter_date" value="{{ $filterDate ?? '' }}"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded text-sm">Filter</button>
                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-3 rounded text-sm">Reset</a>
                </form>
                <!-- END FORM FILTER -->

                <!-- TOMBOL EXPORT PDF -->
                @php
                    // Siapkan parameter untuk URL export
                    $exportParams = [];
                    if ($filterDate) {
                        $exportParams['filter_date'] = $filterDate;
                    }
                @endphp

                <!-- TOMBOL EXPORT PDF DINAMIS -->
                <a href="{{ route('admin.presensi.rekap.pdf', $exportParams) }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center text-sm">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                    </svg>
                    <span>Export PDF</span>
                </a>
            </div>
        </div>
        <table class="table-auto w-full text-left">
            <thead>
                <tr>
                    <th class="px-4 py-2">No</th>
                    <th class="px-4 py-2">Foto</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Lokasi</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Waktu Check In</th>
                    {{-- <th class="px-4 py-2">Waktu Check Out</th> --}}
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Alasan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presensi as $index => $item)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        {{-- Di dalam @foreach loop --}}
                        <td class="px-4 py-2">
                            @php
                                $fotoPath = $item->foto_lokasi ? str_replace('public/', '', $item->foto_lokasi) : null;
                            @endphp
                            @if ($fotoPath)
                                {{-- Buat gambar bisa diklik --}}
                                <button type="button"
                                    @click="isImageModalOpen = true; modalImageUrl = '{{ asset('storage/' . $fotoPath) }}'">
                                    <img src="{{ asset('storage/' . $fotoPath) }}" alt="Foto Lokasi"
                                        class="rounded-full w-10 h-10 object-cover cursor-pointer hover:opacity-80 transition">
                                </button>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $item->user->name }}</td>
                        <td class="px-4 py-2">{{ $item->lokasi }}</td>
                        <td class="px-4 py-2">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-2">{{ $item->clock_in ? $item->clock_in->format('H:i:s') : '-' }}</td>
                        {{-- <td class="px-4 py-2">{{ $item->clock_out ? $item->clock_out->format('H:i:s') : '-' }}</td>
                        --}}
                        <td class="px-4 py-2">{{ $item->status }}</td>
                        <td class="px-4 py-2">{{ $item->catatan ? $item->catatan : '-' }}</td>
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
                        <td class="px-4 py-2"> <span class="btn btn-sm bg-green-400 text-gray-950 my-1">
                                {{ $item->clock_in ? $item->clock_in->format('H:i:s') : '-' }} </span>
                            <span
                                class="btn btn-sm  bg-button text-white">{{ $item->clock_out ? $item->clock_out->format('H:i:s') : '-' }}</span>
                        </td>
                        <td class="px-4 py-2">{{ $item->status }}</td>
                        <td class="px-4 py-2">{{ $item->catatan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    </div>
</x-app-layout>