<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <div class="flex justify-between border-b-2 pb-3">
            <div class="flex items-center">
                <span class="whitespace-nowrap mr-3"> Per Page </span>
                <select @change="getProducts(null)" v-model="perPage"
                class="appearance-none 
                relative block 
                w-24 px-3 py-2 border 
                border-gray-300 
                placeholder-gray-500 
                text-gray-900 
                rounded-md 
                focus:outline-none 
                focus:ring-indigo-500 
                focus:border-indigo-500 
                focus:z-10 sm:text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="ml-3">Found {{ }} products </span>
            </div>
            <div>
                <input v-model="search" @change="getProducts(null)"
                class="appearance-none relative block w-48 px-3 py-2
                border border-gray-300 
                placeholder-gray-500
                text-gray-900
                rounded-md
                focus:outline-none
                focus:border-indigo-500
                focus:z-10
                sm:text-sm"
                placeholder="Type to search products">
            </div>
        </div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <table-head-cell field="id" :sort-field="sortField" :sort-direction="sortDirection" @click="sortProducts('id')"> ID </table-head-cell>
                    <table-head-cell field="image" :sort-field="sortField" :sort-direction="sortDirection" @click="sortProducts('image')"> Image </table-head-cell>
                    <table-head-cell field="title" :sort-field="sortField" :sort-direction="sortDirection" @click="sortProducts('title')">  Title </table-head-cell>
                    <table-head-cell  field="price" :sort-field="sortField" :sort-direction="sortDirection" @click="sortProducts('price')"> Price </table-head-cell>
                    <table-head-cell field="updated_at" :sort-field="sortField" :sort-direction="sortDirection" @click="sortProducts('updated_at')" > Last Updated At </table-head-cell>
                    <table-head-cell field="actions"> Actions </table-head-cell>
                </tr>
            </thead>
            <tbody v-if="products.loading || !products.data.length" >
                <tr>
                    <td colspan="6">
                        <spinner v-if="products"> </spinner>
                        <p v-else class="text-center py-8 text-gray-700">
                            There are no products
                        </p>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr v-for="(product, index) of products.data">
                    <td class="border-b p-2"> {{ product.id }}</td>
                    <td class="border-b p-2">
                        <img class="w-16 h-16 object-cover" :src="product.image_url" :alt="product.title">
                    </td>
                    <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ product.title }}
                    </td>
                    <td class="border-b p-2">
                        {{ product.price }}
                    </td>
                    <td class="border-b p-2">
                        {{ product.updated_at }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-between items-center mt-5">
            <div v-if="products.data.length">
                Showing from {{ products.from }} to {{ products.to }}
            </div>
    <nav
        v-if="products.total > products.limit"
        class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
        aria-label="Pagination"
      >

        <a
          v-for="(link, i) of products.links"
          :key="i"
          :disabled="!link.url"
          href="#"
          @click="getForPage($event, link)"
          aria-current="page"
          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
          :class="[
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 0 ? 'rounded-l-md' : '',
              i === products.links.length - 1 ? 'rounded-r-md' : '',
              !link.url ? ' bg-gray-100 text-gray-700': ''
            ]"
          v-html="link.label"
        >
        </a>
            </nav>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import TableHeadCell from "../../components/core/Table/TableHeadCell.vue";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store";
import {PRODUCTS_PER_PAGE} from "../../constants.js"

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref("");
const products = computed(() => store.state.products);
const sortField = ref('updated_at');
const sortDirection = ref('desc');

function sortProducts(field){
    if(field === sortField.value)
    {
        if(sortDirection.value == 'desc'){
            sortDirection.value = 'asc'
        }else{
            sortDirection.value = 'desc'
        }
    }else{
        sortField.value = field;
        sortDirection.value = 'asc'
    }
}

onMounted(() => {
    getProducts();
})

function getProducts(url = ''){
    store.dispatch('getProducts', {
        url,
        search: search.value,
        perPage: perPage.value
        })
}

function getForPage(event, link){
    event.preventDefault();
    if(!link.url || link.active){
        return
    }

    getProducts(link.url);
}

</script>