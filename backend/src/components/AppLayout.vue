<template>
    <div v-if="currentUser.id" class="min-h-screen bg-gray-200 flex">
        <Sidebar :class="{'-ml-[150px]': !sidebarOpened}" />
        <div class="flex-1">
            <Navbar @toggle-sidebar="toggleSidebar"></Navbar>
            <main class="p-6">
                <router-view> </router-view>
            </main>
        </div>
    </div>
    <div v-else class="min-h-screen bg-gray-200 flex items-center justify-center">
        <Spinner />
    </div>
</template>

<script setup lang="ts">
    import {ref, onMounted, onUnmounted, computed} from 'vue'
    import Sidebar from '../components/Sidebar.vue'
    import Navbar from '../components/Navbar.vue'
    import Spinner from "./core/Spinner.vue"
    import store from "../store";

    const sidebarOpened = ref(true);
    const currentUser = computed(() => store.state.user.data);

    function toggleSidebar() {
        sidebarOpened.value = !sidebarOpened.value
    }
    function updateSidebarState() {
        sidebarOpened.value = window.outerWidth > 768;
    }

    onMounted(() => {
        store.dispatch('getCurrentUser')
        updateSidebarState();
        window.addEventListener('resize', updateSidebarState)
    })

    onUnmounted(() => {
        window.removeEventListener('resize', updateSidebarState)
    })
</script>

<style scoped>

</style>
