<x-app-layout>
    <form method="POST" action="{{ route('password.store') }}" 
    class="w-[400px] mx-auto p-6 my-16 text-center"
    >
        @csrf
        <h2 class="text-2xl font-semibold mb-2"> Please enter your new password </h2>

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-text-input id="email" 
                placeholder="Your Email"
                type="email" 
                name="email" 
                :value="old('email', $request->email)" 
                required autofocus autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-text-input 
                placeholder="New password"
                id="password" 
                type="password" 
                name="password" 
                required autocomplete="new-password" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-text-input 
                id="password_confirmation" 
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation" 
                placeholder="Confirm password"
                required autocomplete="new-password" 
            />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex w-full items-center justify-end mt-4">
            <x-primary-button class="w-full items-center justify-center">
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-app-layout>
