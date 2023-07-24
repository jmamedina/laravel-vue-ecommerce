<template>
     <div class="flex items-center justify-between mb-3">
          <h1 class="text-3xl font-semibold"> Orders </h1>
     </div>
     <orders-table @clickView="viewOrder"></orders-table>
</template>

<script setup lang="ts">
import {ref, computed} from "vue";
    import OrdersTable from "./OrdersTable.vue";
    import store from "../../store"

    const orders = computed( () => store.state.orders );

    function showOrder(product) {
     }

</script>

<style scoped>

</style>
