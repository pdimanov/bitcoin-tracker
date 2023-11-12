<template>
    <Head title="Home"/>
    <GuestLayout>
        <ChartContainer :selectedCurrency="currencies[0]"/>
        <SubscriptionForm :currencies="currencies" :intervals="intervals"/>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Head } from '@inertiajs/vue3';
import SubscriptionForm from "@/Components/Subscription/SubscriptionForm.vue";
import ChartContainer from "@/Components/ChartContainer.vue";

defineProps(['currencies', 'intervals'])
</script>

<style scoped>

</style>
