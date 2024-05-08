<template>
  <!-- メインコンテナー (Main container) -->
  <div v-if="currentUser.id" class="min-h-full bg-gray-200 flex">
    <!-- サイドバー (Sidebar) -->
    <Sidebar :class="{'-ml-[200px]': !sidebarOpened}"/>
    <!-- /サイドバー -->

    <!-- メインコンテンツ (Main content) -->
    <div class="flex-1">
      <!-- ナビゲーションバー (Navbar) -->
      <Navbar @toggle-sidebar="toggleSidebar"></Navbar>
      <!-- コンテンツ (Content) -->
      <main class="p-6">
        <router-view></router-view>
      </main>
      <!-- /コンテンツ (Content) -->
    </div>
    <!-- /メインコンテンツ (Main content) -->
  </div>

  <!-- ロード中に表示されるスピナー (Spinner while loading) -->
  <div v-else class="min-h-full bg-gray-200 flex items-center justify-center">
    <Spinner />
  </div>

  <!-- トースト通知 (Toast notifications) -->
  <Toast />
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, defineProps } from 'vue';
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import store from "../store";
import Spinner from "./core/Spinner.vue";
import Toast from "./core/Toast.vue";

// プロパティ (Props)
const { title } = defineProps({
  title: String // ページのタイトル (Title of the page)
});

// サイドバーが開いているかどうかを示すフラグ (Flag indicating whether the sidebar is opened or not)
// 現在のユーザー情報 (Information of the current user)
const sidebarOpened = ref(true); 
const currentUser = computed(() => store.state.user.data); 

// サイドバーを切り替える関数 (Toggle sidebar function)
function toggleSidebar() {
  sidebarOpened.value = !sidebarOpened.value;
}

// ウィンドウサイズに基づいてサイドバーの状態を更新する関数 (Update sidebar state based on window size)
function updateSidebarState() {
  sidebarOpened.value = window.outerWidth > 768;
}

// ライフサイクルフック (Lifecycle hooks)
// 現在のユーザー情報を取得する (Fetch the information of the current user)
// 国のリストを取得する (Fetch the list of countries)
onMounted(() => {
  store.dispatch('getCurrentUser'); 
  store.dispatch('getCountries'); 
  updateSidebarState();
  window.addEventListener('resize', updateSidebarState);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateSidebarState);
});
</script>

<style scoped>
/* このコンポーネントに特有のスタイル (Scoped styles specific to this component) */
</style>
