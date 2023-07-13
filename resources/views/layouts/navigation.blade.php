<header x-data="{mobileMenuOpen: false}"class="flex justify-between bg-slate-800 text-white px-4">
        <div>
            <a href="/src/" class="block py-navbar-item">
                Logo
            </a>
        </div>
        <!-- mobile menu -->
        <div 
            x-show="mobileMenuOpen"
            class="block z-10 fixed top-0 bottom-0 h-full w-[220px] bg-slate-900 shadow-2xl transition-all md:hidden"
            :class="mobileMenuOpen ? 'left-0' : '-left-[220px]'"
            @click.outside="mobileMenuOpen = false"
        >
            <ul>
                <li class="mt-4">
                    <a href="/src/index.html" 
                        class="flex items-center px-2 py-2 transition-all hover:bg-slate-700"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                       Home 
                    </a>
                </li>
                <li><a href="#" class="block px-2 py-2 transition-all hover:bg-slate-700"> Categories </a> </li>
                <li><a href="#" class="block px-2 py-2 transition-all hover:bg-slate-700"> Something </a> </li>
            </ul>
            <ul>
                <li>
                    <a href="/src/cart.html" class ="flex items-center px-2 py-2 transition-all hover:bg-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                  </svg>
                  Cart </a> </li>
                <li x-data="{ open: false }"
                class="relative">
                    <a 
                        @click="open = !open"
                        class="cursor-pointer flex items-center px-2 py-2 transition-all hover:bg-slate-700 justify-between">                 
                        My Account
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                          </svg>
                    </a> 
                    <ul 
                        @click.outside="open = false"
                        x-show="open" 
                        x-transition 
                        class="bg-slate-800 w-full z-10 right-0 py-2"
                    >
                        <li>
                            <a href="/src/profile.html" class="flex items-center py-1 px-4 transition-all hover:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                  </svg>                                  
                                    My Profile 
                            </a> 
                        </li>
                        <li>
                            <a href="/src/watchlist.html" class="flex items-center py-1 px-4 transition-all hover:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                  </svg>                                  
                                Watchlist 
                            </a>
                        <li>
                        <li>
                            <a href="/src/orders.html" class="flex items-center py-1 px-4 transition-all hover:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                  </svg>                                  
                                    Orders 
                            </a> 
                        </li>
                        <li>
                            <a href="/src/logout.html" class="flex items-center py-1 px-4 transition-all hover:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                  </svg>         
                                Logout        
                            </a> 
                        </li>
                    </ul>
                </li>
                <li class="my-3"><a href="/src/login.html" class="flex items-center px-2 py-2 transition-all hover:bg-slate-700"> 
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                      </svg>
                      Login </a> </li>
                <li class="px-3"><a href="/src/signup.html" 
                    class="flex py-2 px-3 py-2 rounded bg-emerald-500 hover:bg-emerald-600 transition-colors"
                    > 
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                      </svg>                      
                    Signup for free</a> </li>   
            </ul>
        </div>
        <!-- mobile menu end -->
        
        <!-- main -->
        <nav class ="hidden md:block">
            <ul class="grid grid-flow-col">
                <li><a href="/src/index.html" class="flex item-center px-navbar-item py-navbar-item transition-all hover:bg-slate-700"> 
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                      </svg>                      
                    Home </a> </li>
                <li><a href="#" class="block px-navbar-item py-navbar-item transition-all hover:bg-slate-700"> Categories </a> </li>
                <li><a href="#" class="block px-navbar-item py-navbar-item transition-all hover:bg-slate-700"> Something </a> </li>
            </ul>
        </nav>
        <nav class="hidden md:block">
            <ul class="grid grid-flow-col items-center">
                <li>
                    <a href="/src/cart.html" class ="flex items-center px-navbar-item py-navbar-item transition-all hover:bg-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                        Cart 
                        <span  x-show="productCounter" x-transition x-text="productCounter"></span>
                    </a> 
                </li>
                <li x-data="{open: false}" class="relative">
                    <a 
                    @click="open = !open"
                    class="cursor-pointer flex items-center px-navbar-item py-navbar-item transition-all hover:bg-slate-700">                 
                        My Account
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                          </svg>
                    </a> 
                    <ul 
                    @click.outside="open = false"
                     x-show="open" x-transition class="absolute z-10 bg-slate-800 w-40 right-0 py-2">
                        <li>
                            <a href="/src/profile.html" class="flex items-center py-1 px-4 transition-all hover:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                  </svg>                                  
                                    My Profile 
                            </a> 
                        </li>
                        <li>
                            <a href="/src/watchlist.html" class="flex items-center py-1 px-4 transition-all hover:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                  </svg>                                  
                                Watchlist 
                            </a>
                        <li>
                        <li>
                            <a href="/src/orders.html" class="flex items-center py-1 px-4 transition-all hover:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                  </svg>                                  
                                    Orders 
                            </a> 
                        </li>
                        <li>
                            <a href="/src/logout.html" class="flex items-center py-1 px-4 transition-all hover:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                  </svg>         
                                Logout        
                            </a> 
                        </li>
                    </ul>
                </li>
                <li class=""><a href="/src/login.html" class="flex items-center px-navbar-item py-navbar-item  transition-all hover:bg-slate-700"> 
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                      </svg>
                      Login </a> </li>
                <li class=""><a href="/src/signup.html" 
                    class="flex py-2 px-3 py-navbar-item rounded bg-emerald-500 hover:bg-emerald-600 transition-colors ml-4"
                    > 
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                      </svg>                      
                    Signup for free</a> </li>   
            </ul>
        </nav>
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="block md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
              </svg>                  
        </button>
    </header>