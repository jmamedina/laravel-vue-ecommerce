<template>
     <div class="flex items-center justify-between mb-3">
          <h1 class="text-3xl font-semibold"> Products </h1>
          <button type="submit"
          @click="showProductModal()" 
          class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 foxus:ring-offset-2 focus:ring-indigo-500">
               Add new Product
          </button>
     </div>
     <products-modal v-model = "showModal" :product="productModel" ></products-modal>
     <products-table @clickEdit="editProduct"></products-table>
</template>

<script setup lang="ts">
import {ref} from "vue";
    import ProductsTable from "./ProductsTable.vue";
    import ProductsModal from "./ProductsModal.vue"
    import store from "../../store"

    const productModel = ref({
     id: '',
     title: '',
     image: '',
     description: '',
     price: '',
     // published: ''
    })

    const showModal = ref(false);

    function showProductModal(){
          showModal.value = true;
    }

    function editProduct(product) {
          store.dispatch('getProduct', product)
          .then(({data}) => {
               productModel.value = data
               showProductModal();
          })
     }

</script>

<style scoped>

</style>
