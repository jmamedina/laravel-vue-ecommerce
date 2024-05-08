<template>
  <!-- Product Form Container -->
  <div class="flex items-center justify-between mb-3">
    <!-- Product Form Heading -->
    <h1 v-if="!loading" class="text-3xl font-semibold">
      {{ product.id ? `Update product: "${product.title}"` : 'Create new Product' }}
    </h1>
  </div>
  <!-- Product Form -->
  <div class="bg-white rounded-lg shadow animate-fade-in-down">
    <!-- Loading Spinner -->
    <Spinner v-if="loading"
             class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center z-50"/>
    <!-- Product Form -->
    <form v-if="!loading" @submit.prevent="onSubmit">
      <!-- Form Grid -->
      <div class="grid grid-cols-3">
        <!-- Left Column -->
        <div class="col-span-2 px-4 pt-5 pb-4">
          <!-- Custom Input: Product Title -->
          <CustomInput class="mb-2" v-model="product.title" label="Product Title" :errors="errors['title']"/>
          <!-- Custom Input: Product Description -->
          <CustomInput type="richtext" class="mb-2" v-model="product.description" label="Description" :errors="errors['description']"/>
          <!-- Custom Input: Product Price -->
          <CustomInput type="number" class="mb-2" v-model="product.price" label="Price" prepend="¥" :errors="errors['price']"/>
          <!-- Custom Input: Product Quantity -->
          <CustomInput type="number" class="mb-2" v-model="product.quantity" label="Quantity" :errors="errors['quantity']"/>
          <!-- Custom Input: Product Published -->
          <CustomInput type="checkbox" class="mb-2" v-model="product.published" label="Published" :errors="errors['published']"/>
          <!-- Treeselect: Product Categories -->
          <treeselect v-model="product.categories" :multiple="true" :options="options" :errors="errors['categories']"/>
        </div>
        <!-- Right Column -->
        <div class="col-span-1 px-4 pt-5 pb-4">
          <!-- Image Preview Component -->
          <image-preview v-model="product.images"
                         :images="product.images"
                         v-model:deleted-images="product.deleted_images"
                         v-model:image-positions="product.image_positions"/>
        </div>
      </div>
      <!-- Form Footer -->
      <footer class="bg-gray-50 rounded-b-lg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <!-- Save Button -->
        <button type="submit"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save
        </button>
        <!-- Save & Close Button -->
        <button type="button"
                @click="onSubmit($event, true)"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save & Close
        </button>
        <!-- Cancel Button -->
        <router-link :to="{name: 'app.products'}"
                     class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                     ref="cancelButtonRef">
          Cancel
        </router-link>
      </footer>
    </form>
  </div>
</template>

<script setup>
// Imports
import {onMounted, ref} from 'vue'
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import {useRoute, useRouter} from "vue-router";
import ImagePreview from "../../components/ImagePreview.vue";
// Import the Treeselect component
import Treeselect from 'vue3-treeselect'
// Import the Treeselect component styles
import 'vue3-treeselect/dist/vue3-treeselect.css'
import axiosClient from "../../axios.js";

// Variables
const route = useRoute()
const router = useRouter()
const product = ref({
  id: null,
  title: null,
  images: [],
  deleted_images: [],
  image_positions: {},
  description: '',
  price: null,
  quantity: null,
  published: false,
  categories: []
})
const errors = ref({});
const loading = ref(false)
const options = ref([])

// Emit event for model update
const emit = defineEmits(['update:modelValue', 'close'])

// Fetch data on component mount
onMounted(() => {
  // Fetch product data if editing
  if (route.params.id) {
    loading.value = true
    store.dispatch('getProduct', route.params.id)
      .then((response) => {
        loading.value = false;
        product.value = response.data
      })
  }

  // Fetch category options
  axiosClient.get('/categories/tree')
    .then(result => {
      options.value = result.data
    })
})

// Form submit handler
function onSubmit($event, close = false) {
  loading.value = true
  errors.value = {};
  product.value.quantity = product.value.quantity || null
  if (product.value.id) {
    // Update existing product
    store.dispatch('updateProduct', product.value)
      .then(response => {
        loading.value = false;
        if (response.status === 200) {
          product.value = response.data
          store.commit('showToast', 'Product was successfully updated');
          store.dispatch('getProducts')
          if (close) {
            router.push({name: 'app.products'})
          }
        }
      })
      .catch(err => {
        loading.value = false;
        errors.value = err.response.data.errors
      })
  } else {
    // Create new product
    store.dispatch('createProduct', product.value)
      .then(response => {
        loading.value = false;
        if (response.status === 201) {
          product.value = response.data
          store.commit('showToast', 'Product was successfully created');
          store.dispatch('getProducts')
          if (close) {
            router.push({name: 'app.products'})
          } else {
            product.value = response.data
            router.push({name: 'app.products.edit', params: {id: response.data.id}})
          }
        }
      })
      .catch(err => {
        loading.value = false;
        errors.value = err.response.data.errors
      })
  }
}
</script>
