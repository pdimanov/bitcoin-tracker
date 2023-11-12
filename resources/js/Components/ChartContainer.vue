<template>
    <Chart
        v-if="historyData"
        :data="data"
        :labels="labels"
    />
</template>

<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import Chart from "@/Components/Chart.vue";

const props = defineProps({
    selectedCurrency: {
        type: String
    }
})

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
    data.value = historyData.value.data[props.selectedCurrency]
    labels.value = historyData.value.data.periods
}
</script>

<style scoped>

</style>
