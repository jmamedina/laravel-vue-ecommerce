<template>
  <!-- Order Details Container -->
  <div v-if="order">

    <!-- Order Details Section -->
    <div>
      <!-- Order Details Heading -->
      <h2 class="flex justify-between items-center text-xl font-semibold pb-2 border-b border-gray-300">
        Order Details
        <!-- Order Status Component -->
        <OrderStatus :order="order" />
      </h2>
      <!-- Order Details Table -->
      <table>
        <tbody>
        <!-- Order Number -->
        <tr>
          <td class="font-bold py-1 px-2">Order #</td>
          <td>{{ order.id }}</td>
        </tr>
        <!-- Order Date -->
        <tr>
          <td class="font-bold py-1 px-2">Order Date</td>
          <td>{{ order.created_at }}</td>
        </tr>
        <!-- Order Status -->
        <tr>
          <td class="font-bold py-1 px-2">Order Status</td>
          <td>
            <!-- Order Status Dropdown -->
            <select v-model="order.status" @change="onStatusChange">
              <option v-for="status of orderStatuses" :value="status">{{status}}</option>
            </select>
          </td>
        </tr>
        <!-- Subtotal -->
        <tr>
          <td class="font-bold py-1 px-2">SubTotal</td>
          <td>{{ $filters.currencyJPY(order.total_price) }}</td>
        </tr>
        </tbody>
      </table>
    </div>
    <!--/ Order Details Section -->

    <!-- Customer Details Section -->
    <div>
      <!-- Customer Details Heading -->
      <h2 class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300">Customer Details</h2>
      <!-- Customer Details Table -->
      <table>
        <tbody>
        <!-- Full Name -->
        <tr>
          <td class="font-bold py-1 px-2">Full Name</td>
          <td>{{ order.customer.first_name }} {{ order.customer.last_name }}</td>
        </tr>
        <!-- Email -->
        <tr>
          <td class="font-bold py-1 px-2">Email</td>
          <td>{{ order.customer.email }}</td>
        </tr>
        <!-- Phone -->
        <tr>
          <td class="font-bold py-1 px-2">Phone</td>
          <td>{{ order.customer.phone }}</td>
        </tr>
        </tbody>
      </table>
    </div>
    <!--/ Customer Details Section -->

    <!-- Addresses Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <!-- Billing Address -->
      <div>
        <h2 class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300">Billing Address</h2>
        <!-- Billing Address Details -->
        <div>
          {{ order.customer.billingAddress.address1 }}, {{ order.customer.billingAddress.address2 }} <br>
          {{ order.customer.billingAddress.city }}, {{ order.customer.billingAddress.zipcode }} <br>
          {{ order.customer.billingAddress.state }}, {{ order.customer.billingAddress.country }} <br>
        </div>
        <!--/ Billing Address Details -->
      </div>
      <!-- Shipping Address -->
      <div>
        <h2 class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300">Shipping Address</h2>
        <!-- Shipping Address Details -->
        <div>
          {{ order.customer.shippingAddress.address1 }}, {{ order.customer.shippingAddress.address2 }} <br>
          {{ order.customer.shippingAddress.city }}, {{ order.customer.shippingAddress.zipcode }} <br>
          {{ order.customer.shippingAddress.state }}, {{ order.customer.shippingAddress.country }} <br>
        </div>
        <!--/ Shipping Address Details -->
      </div>
    </div>
    <!--/ Addresses Details Section -->

    <!-- Order Items Section -->
    <div>
      <h2 class="text-xl font-semibold mt-6 pb-2 border-b border-gray-300">Order Items</h2>
      <!-- Order Items -->
      <div v-for="item of order.items">
        <!-- Order Item -->
        <div class="flex flex-col sm:flex-row items-center  gap-4">
          <!-- Product Image -->
          <a href="#" class="w-36 h-32 flex items-center justify-center overflow-hidden">
            <img :src="item.product.image" class="object-cover" alt=""/>
          </a>
          <!-- Product Details -->
          <div class="flex flex-col justify-between flex-1">
            <!-- Product Title -->
            <div class="flex justify-between mb-3">
              <h3>{{ item.product.title }}</h3>
            </div>
            <!-- Product Quantity and Price -->
            <div class="flex justify-between items-center">
              <!-- Quantity -->
              <div class="flex items-center">Qty: {{ item.quantity }}</div>
              <!-- Price -->
              <span class="text-lg font-semibold"> {{ $filters.currencyJPY(item.unit_price) }} </span>
            </div>
          </div>
        </div>
        <!--/ Order Item -->
        <!-- Divider -->
        <hr class="my-3"/>
      </div>
      <!--/ Order Items -->
    </div>
    <!--/ Order Items Section -->

  </div>
</template>

<script setup>
// Imports
import {onMounted, ref} from "vue";
import store from "../../store";
import {useRoute} from "vue-router";
import axiosClient from "../../axios.js";
import OrderStatus from "./OrderStatus.vue";

// Variables
const route = useRoute();
const order = ref(null);
const orderStatuses = ref([]);

// Functions
onMounted(() => {
  // Get order details
  store.dispatch('getOrder', route.params.id)
    .then(({data}) => {
      order.value = data;
    });

  // Get order statuses
  axiosClient.get(`/orders/statuses`)
    .then(({data}) => {
      orderStatuses.value = data;
    });
});

function onStatusChange() {
  // Update order status
  axiosClient.post(`/orders/change-status/${order.value.id}/${order.value.status}`)
    .then(({data}) => {
      store.commit('showToast', `Order status was successfully changed into "${order.value.status}"`);
    });
}
</script>

<style scoped>

</style>
