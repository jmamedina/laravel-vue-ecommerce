<template>
    <div class="min-h-screen bg-gray-200 flex">
        <Sidebar :class="{'-ml-[150px]': !sidebarOpened}" />
        <div class="flex-1">
            <Navbar @toggle-sidebar="toggleSidebar"></Navbar>
            <main class="p-6">
                <router-view> </router-view>
            </main>
        </div>
    </div>
</template>

<script setup>
    import {ref, computed, onMounted, onUnmounted} from 'vue'
    import Sidebar from '../components/Sidebar.vue'
    import Navbar from '../components/Navbar.vue'

    const sidebarOpened = ref(true);

    function toggleSidebar() {
        sidebarOpened.value = !sidebarOpened.value
    }
    function updateSidebarState() {
        sidebarOpened.value = window.outerWidth > 768;
    }

    onMounted(() => {
        updateSidebarState();
        window.addEventListener('resize', updateSidebarState)
    })

    onUnmounted(() => {
        window.removeEventListener('resize', updateSidebarState)
    })
</script>

<style scoped>

</style>
