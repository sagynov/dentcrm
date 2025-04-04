<script setup lang="ts">
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';
import Chart from 'primevue/chart';
import { onMounted, ref } from 'vue';

const data_keys = ref();
const data_values = ref();
const total = ref(0);

const period = ref('week');

onMounted(() => {
    loadMetric('week');
});

const loadMetric = (period: string) => {
    axios.get(route('owner.appointment-metric'), { params: { period } }).then(({ data }) => {
        data_keys.value = data.data_keys;
        data_values.value = data.data_values;
        total.value = data.total;
    });
};

const changeDate = (event: any) => {
    loadMetric(event);
};

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: data_keys,
        datasets: [
            {
                label: trans('Number of records'),
                data: data_values,
                fill: false,
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                tension: 0.4,
            },
        ],
    };
};

const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        maintainAspectRatio: false,
        aspectRatio: 0.6,
        plugins: {
            legend: {
                labels: {
                    color: textColor,
                },
            },
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                },
                grid: {
                    color: surfaceBorder,
                },
            },
            y: {
                ticks: {
                    color: textColorSecondary,
                },
                grid: {
                    color: surfaceBorder,
                },
            },
        },
    };
};
const chartData = ref();
const chartOptions = ref();

chartData.value = setChartData();
chartOptions.value = setChartOptions();
</script>
<template>
    <div class="flex flex-col gap-4">
        <h2 class="font-medium text-gray-700">{{ trans('Appointments') }}</h2>
        <div class="w-[10rem]">
            <Select @update:model-value="changeDate" v-model="period">
                <SelectTrigger>
                    <SelectValue :placeholder="trans('Select')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="week">
                        {{ trans('Week') }}
                    </SelectItem>
                    <SelectItem value="month">
                        {{ trans('Month') }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>
        <div>{{ trans('Total: :count', { count: '' + total }) }}</div>
        <div>
            <Chart type="line" :data="chartData" :options="chartOptions" class="h-[30rem]" />
        </div>
    </div>
</template>
