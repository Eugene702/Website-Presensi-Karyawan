<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

layout('layouts.guest');

form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
};

?>
<div class=" flex justify-center h-screen md:h-auto flex-col">
    <div class="flex justify-center ">
        <a href="/" wire:navigate>
            <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
        </a>
    </div>
    <P class="bg-custom-gradient text-xl text-center font-extrabold my-5 mb-10"> Selangkah Lebih Dekat Dengan Suksesmu</P>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full  " type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full bg-transparent"
                type="password"
                name="password"
                required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>




        <div class="flex items-center justify-end mt-4">


            <x-primary-button class=" bg-button w-full r">
                <p class="mx-auto text-white my-2 font-extrabold ">Masuk <span class="hidden">{{ __('Log in') }}</span></p>
            </x-primary-button>
        </div>
    </form>
    <p class="text-center mt-3 text-black">Belum ada akun ? <a href="{{ route('register') }}" class="bg-custom-gradient"> Daftar disini <span class="hidden">{{ __('Register') }}</span></a></p>
   
</div>