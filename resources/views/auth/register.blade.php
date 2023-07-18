<x-app-layout>
<main class="p-5">
      <form
        action="{{ route('register') }}"
        method="post"
        class="w-[400px] mx-auto p-6 my-16"
        >
      @csrf
        <h2 class="text-2xl font-semibold text-center mb-4">Create an account</h2>
        <p class="text-center text-gray-500 mb-3">
          or
          <a
            href="{{ route('login') }}"
            class="text-sm text-purple-700 hover:text-purple-600"
            >login with existing account</a
          >
        </p>
        <div class="mb-4">
        <x-text-input 
            placeholder="Your name"
            type="text"
            name="name"
            id="name"
            required
          />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        </p>
        <div class="mb-4">
        <x-text-input 
            placeholder="Your Email"
            type="email"
            name="email"
            id="email"
            required
          />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mb-4">
        <x-text-input 
            placeholder="Password"
            type="password"
            name="password"
            id="password"
            required
          />
          <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        </div>
        <div class="mb-4">
        <x-text-input 
            placeholder="Repeat Password"
            type="password"
            name="password_confirmation"
            id="password_confirmation"
            required
          />
          <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button
          class="btn-emerald justify-center w-full"
        >
          Signup
        </button>
      </form>
    </main>  
</x-app-layout>
