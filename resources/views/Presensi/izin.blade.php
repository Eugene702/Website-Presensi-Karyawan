<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Ajukan Izin Presensi</h2>
        <form method="POST" action="{{ route('presensi.izinSubmit') }}">
            @csrf
            <div class="mb-4">
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
            <div class="flex justify-end">
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Submit</button>
            </div>
        </form>
    </div>
</x-app-layout>
