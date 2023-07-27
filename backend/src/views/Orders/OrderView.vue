<template>
    <h1> {{ route }} </h1>
</template>

<script setup lang="ts">
    import {computed, onMounted, ref} from "vue";
    import store from "../../store";
    import {useRoute} from "vue-router";

    const route = useRoute()

    store.dispatch('getOrder', route.params.id)

</script>

