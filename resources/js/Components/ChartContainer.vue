<template>
    <Chart
        v-if="historyData"
        :data="data"
        :labels="labels"
    />
    <ChartCurrencyFilter @chartCurrencyChange="updateChartCurrency" :currencies="currencies"/>
</template>

<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import Chart from "@/Components/Chart.vue";
import ChartCurrencyFilter from "@/Components/ChartCurrencyFilter.vue";

const props = defineProps({
    currencies: {
        type: Array
    }
})

const chartCurrency = ref(null)
const historyData = ref(null)
const data = ref([])
const labels = ref([])

onMounted(() => {
    axios
        .get(route('api.price.historyPeriod'))
        .then(response => {
            historyData.value = response.data
            passInitialChartData()
        })
})

function passInitialChartData() {
    chartCurrency.value = props.currencies[0]
    data.value = historyData.value.data[chartCurrency.value]
    labels.value = historyData.value.data.periods
}

function updateChartCurrency(newCurrency) {
    chartCurrency.value = newCurrency
    data.value = historyData.value.data[chartCurrency.value]
}
</script>

<style scoped>

</style>
