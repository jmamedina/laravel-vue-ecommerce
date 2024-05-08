<template>
  <!-- カテゴリーの見出しと新しいカテゴリーを追加するボタン -->
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Categories</h1>
    <!-- Button to add a new category -->
    <button type="button"
            @click="showAddNewModal()"
            class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    >
      Add new Category
    </button>
  </div>
  <!-- Categories table and modal -->
  <CategoriesTable @clickEdit="editCategory"/>
  <CategoryModal v-model="showCategoryModal" :category="categoryModel" @close="onModalClose"/>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import CategoryModal from "./CategoryModal.vue";
import CategoriesTable from "./CategoriesTable.vue";

// Default category object
const DEFAULT_CATEGORY = {
  id: '',
  title: '',
  description: '',
  image: '',
  price: ''
}

// Computed property to get categories from the store
const categories = computed(() => store.state.categories);

// Variables referencing category model and modal visibility
const categoryModel = ref({...DEFAULT_CATEGORY})
const showCategoryModal = ref(false);

// Function to show the modal for adding a new category
function showAddNewModal() {
  showCategoryModal.value = true
}

// Function to edit a category
function editCategory(u) {
    categoryModel.value = u;
    showAddNewModal();
}

// Function to handle modal closure
function onModalClose() {
  categoryModel.value = {...DEFAULT_CATEGORY}
}
</script>

<style scoped>

</style>
