<template>
     <div class="flex items-center justify-between mb-3">
          <h1 class="text-3xl font-semibold"> Products </h1>
          <button tyle="button"
          @click="showAddNewModal()" 
          class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 foxus:ring-offset-2 focus:ring-indigo-500">
               Add new Product
          </button>
     </div>
     <products-table></products-table>
</template>

<script setup lang="ts">
    import ProductsTable from "./ProductsTable.vue";
    import store from "../../store"
</script>

<style scoped>

</style>
