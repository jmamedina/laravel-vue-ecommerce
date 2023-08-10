<template>
  <div class="flex items-center justify-between mb-3">
    <h1 class="text-3xl font-semibold">Customers</h1>
  </div>
  <customers-table @clickEdit="editCustomer"></customers-table>
  <CustomerModal v-model="showCustomerModal" :customer="customerModel" @close="onModalClose"/>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import CustomerModal from "./CustomerModal.vue";
import CustomersTable from "./CustomersTable.vue";

const DEFAULT_USER = {
}

const customers = computed(() => store.state.customers);

const customerModel = ref({...DEFAULT_USER})
const showCustomerModal = ref(false);

function showAddNewModal() {
  showCustomerModal.value = true
}

function editCustomer(c) {
  store.dispatch('getCustomer', c)
    .then(({data}) => {
      console.log(data)
      customerModel.value = data;
      showAddNewModal();
    })
}

function onModalClose() {
  customerModel.value = {...DEFAULT_USER}
}
</script>

<style scoped>

</style>
