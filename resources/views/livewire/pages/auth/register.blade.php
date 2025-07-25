<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule; // Import Rule facade

use function Livewire\Volt\layout;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;

layout('layouts.guest');

// Tambahkan state untuk npm dan jurusan
state([
    'name' => '',
    'email' => '',
    'npm' => '', // State untuk NPM
    'jurusan' => '', // State untuk Jurusan
    'password' => '',
    'password_confirmation' => ''
]);

// Tambahkan aturan validasi untuk npm dan jurusan
rules([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    'npm' => ['required', 'string', 'max:20', 'unique:' . User::class], // Validasi untuk NPM
    'jurusan' => [ // Validasi untuk Jurusan
        'required',
        'string',
        Rule::in(['Biologi', 'Teknik Kimia', 'Ilmu hukum', 'Teknik Geodesi', 'Biologi Terapan'])
    ],
    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
]);

$register = function () {
    $validated = $this->validate();

    $validated['password'] = Hash::make($validated['password']);

    // 'npm' dan 'jurusan' akan otomatis tersimpan karena sudah divalidasi
    event(new Registered($user = User::create($validated)));

    Auth::login($user);

    $this->redirect(route('dashboard', absolute: false), navigate: true);
};

?>

<div class="flex justify-center h-screen md:h-auto flex-col">
    <div class="flex justify-center ">
       
    </div>
    <P class="bg-custom-gradient text-xl text-center font-extrabold my-5 mb-10"> KKN Bermanfaat</P>

    <form wire:submit="register">
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="npm" :value="__('NPM')" />
            <x-text-input wire:model="npm" id="npm" class="block mt-1 w-full" type="text" name="npm" required />
            <x-input-error :messages="$errors->get('npm')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="jurusan" :value="__('Jurusan')" />
            <select wire:model="jurusan" id="jurusan" name="jurusan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="" disabled>Pilih Jurusan Anda</option>
                <option value="Biologi">Biologi</option>
                <option value="Teknik Kimia">Teknik Kimia</option>
                <option value="Ilmu hukum">Ilmu hukum</option>
                <option value="Teknik Geodesi">Teknik Geodesi</option>
                <option value="Biologi Terapan">Biologi Terapan</option>
            </select>
            <x-input-error :messages="$errors->get('jurusan')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class=" bg-button w-full">
                <p class="mx-auto text-white my-2 font-extrabold ">Daftar <span class="hidden">{{ __('Register') }}</span></p>
            </x-primary-button>
        </div>
    </form>
    <p class="text-center mt-3 text-black">Sudah ada akun ? <a href="{{ route('login') }}" class="bg-custom-gradient"> Masuk <span class="hidden">{{ __('Log in') }}</span></a></p>
</div>