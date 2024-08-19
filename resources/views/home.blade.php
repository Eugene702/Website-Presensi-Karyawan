<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Home</title>
</head>

<body class="bg-gray-100 shadow flex flex-col min-h-screen">
    <header class="flex justify-between items-center p-6 bg-white shadow-md">
        <!-- Teks yang muncul di mobile -->
        <h1 class="text-lg md:text-2xl font-bold bg-custom-gradient block md:hidden">FHCI</h1>

        <!-- Teks yang muncul di layar yang lebih besar dari mobile -->
        <h1 class="text-lg md:text-2xl font-bold bg-custom-gradient hidden md:block">FORUM HUMAN CAPITAL INDONESIA</h1>


        <div class="font-medium bg-custom-gradient">
            @if (Route::has('login'))
            <livewire:welcome.navigation />
            @endif
        </div>
    </header>

    <main class="mx-auto flex-grow px-4 flex items-center justify-center">
        <div class="flex items-center justify-center flex-col space-y-4 text-center">
            <h2 class="text-2xl text-gray-800 font-bold">Website Presensi Karyawan FHCI</h2>
            <img src="{{ asset('/image/FHCI_logo.png') }}" alt="FHCI Logo" class="w-64 md:w-96">
        </div>
    </main>
</body>

</html>