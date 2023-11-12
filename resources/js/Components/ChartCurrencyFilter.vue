<script setup>
import {onMounted, ref} from 'vue';

const props = defineProps({
    currencies: {
        type: Array
    }
})

const filterCurrency = ref(null)

onMounted(() => {
    filterCurrency.value = props.currencies[0]
})
</script>

<template>
    <div @change="$emit('chartCurrencyChange', filterCurrency)" class="flex justify-center gap-4">
        <div v-for="currency in currencies" class="flex items-center gap-1">
            <input type="radio" :id="currency" :value="currency" v-model="filterCurrency"/>
            <label :for="currency">{{ currency }}</label>
        </div>
    </div>
</template>

<style scoped>
</style>
