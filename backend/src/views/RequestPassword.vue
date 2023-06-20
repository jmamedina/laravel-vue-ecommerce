<template>
    <guest-layout title="Request new password">
    <div class="mt-3 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="#" method="POST">
        <div>
          <div class="mt-2">
            <input placeholder="Email Address" id="email" name="email" type="email" autocomplete="email" required="" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
          </div>
        </div>
        <div>
          <div class="flex items-center justify-between">
            <div class="text-sm">
              <router-link :to="{name: 'resetpassword'}" href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Reset your password?</router-link>
            </div>
          </div>
        </div>
        <div>
          <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
        </div>
      </form>
    </div>
    </guest-layout>
</template>

<script setup>
    import { LockClosedIcon} from '@heroicons/vue/solid'
    import GuestLayout from '../components/GuestLayout.vue'
</script>