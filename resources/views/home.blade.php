<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Home</title>
</head>

<body class="bg-gray-50 shadow flex flex-col min-h-screen">
    <header class="flex justify-between items-center p-6 bg-white shadow-md">
        <!-- Teks yang muncul di mobile -->
        <h1 class="text-lg md:text-2xl font-bold text-blue-700 block md:hidden">KKN MAHASISWA UNIVERSITAS LAMPUNG</h1>

        <!-- Teks yang muncul di layar yang lebih besar dari mobile -->
        <h1 class="text-lg md:text-2xl font-bold text-blue-700 hidden md:block">KKN MAHASISWA UNIVERSITAS LAMPUNG</h1>


        <div class="font-medium text-blue-700 text-xl">
            @if (Route::has('login'))
            <livewire:welcome.navigation />
            @endif
        </div>
    </header>

    <main class="mx-auto flex-grow px-4 flex items-center justify-center ">
        <div class="flex items-center justify-center flex-col space-y-4 text-center">
            <h2 class="text-2xl text-gray-800 font-bold">Presensi KKN Mahasiswa Universitas Lampung</h2>
            <img src="{{ asset('/image/logo.jpg') }}" alt="FHCI Logo" class="w-64 md:w-96">
        </div>
    </main>
</body>

</html>