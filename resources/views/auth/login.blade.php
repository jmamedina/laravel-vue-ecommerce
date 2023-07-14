<x-app-layout>

    <form action="{{ route('login') }}" method="post" class="w-[400px] mx-auto p-6 my-16 text-center">
        @csrf
            <h2 class="text-2xl font-semibold mb-2"> Login to your account </h2>
            <p 
                class="mb-4"> or 
                <a 
                    class="text-purple-600 hover:text-purple-500" 
                    href="/src/signup.html"> 
                    Create new one
                </a>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </p>
                <div class="mb-3">
                    <x-text-input 
                        name="email" 
                        id="email" 
                        type="email"
                        :value="old('email')"
                        placeholder="Your Email"
                    />
                </div>
                <div class="mb-3">
                    <x-text-input 
                        id="password" 
                        name="password" 
                        type="password" 
                        placeholder="Your Password"
                    />
                </div>
                <div class="mb-3 flex justify-between">
                    <div class="flex items-center">
                        <input 
                            id ="rememberMe" 
                            type="checkbox" 
                            class="mr-3 text-purple-600 focus:ring-purple-600"
                        />
                        <label class="text-gray-700" for="rememberMe">
                            Remember me
                        </label>
                    </div>
                    <a 
                        class="text-purple hover:text-purple-500 text-purple-600" 
                        href="/src/password-reset.html"
                    >
                        Reset your password 
                    </a>            
                </div>
                <button 
                    type="submit"
                    class=" btn-emerald justify-center w-full mt-3"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>              
                    Login
                </button>    
        </form>       

    <!-- Session Status -->

  
</x-app-layout>
