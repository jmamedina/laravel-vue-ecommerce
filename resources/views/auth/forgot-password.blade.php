<x-app-layout>
 <!-- Session Status -->
 <x-auth-session-status class="mb-4" :status="session('status')" />
<form method="POST" action="{{ route('password.email') }}" class="text-center w-[400px] mx-auto p-6 my-16">
    @csrf
        <h2 class="text-2xl font-semibold mb-2"> Enter your email to reset password </h2>
        <p class="mb-4"> or <a class="text-purple-600 hover:text-purple-500" href="{{ route('login') }}"> Login with exisiting account </a></p>
       
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div class="mb-3">
                <x-text-input 
                    class="w-full rounded border-gray-300 focus:border-purple-600 focus:ring-purple-600" 
                    type="email" 
                    id="email"
                    :value="old('email')"
                    placeholder="Your Email"
                    autofocus
                />
            </div>
            <button class=" btn-emerald justify-center w-full mt-3">        
            Submit
        </button>    
    </form>
</x-app-layout>
