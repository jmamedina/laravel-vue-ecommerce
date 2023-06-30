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
            <tbody >
                <tr>
                    <td colspan="6">
                        <spinner v-if="products"> </spinner>
                        <p v-else class="text-center py-8 text-gray-700">
                            There are no products
                        </p>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    {{ products }}
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import TableHeadCell from "../../components/core/Table/TableHeadCell.vue";
import Spinner from "../../components/core/Spinner.vue";
import store from "../../store";

const perPage = ref(10)
const products = computed(() => store.state);
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

function getProducts(){
    store.dispatch('getProducts')
}

</script>