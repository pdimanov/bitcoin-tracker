<template>
    <h1 class="text-xl  text-center text-[#f87979]">Bitcoin prices</h1>
    <Line
        id="bitcoin-chart"
        :data="chartData"
        :options="chartOptions"
    />
</template>

<script setup>
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
} from 'chart.js'
import {Line} from 'vue-chartjs'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
)

const props = defineProps({
    labels: {
        type: Array,
        required: true
    },
    data: {
        type: Array,
        required: true
    }
})

const chartData = {
    labels: props.labels,
    datasets: [
        {
            label: 'Bitcoin price',
            backgroundColor: '#f87979',
            data: props.data
        }
    ]
}

const chartOptions = {
    responsive: true,
    scales: {
        x: {
            display: false
        },
    }
}

</script>

<style scoped>

</style>
