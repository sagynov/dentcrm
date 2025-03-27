<script setup lang="ts">
import axios from 'axios';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { trans } from 'laravel-vue-i18n';
import Chart from 'primevue/chart';
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3'


const data_keys = ref();
const data_values = ref();

const period = ref('week');

onMounted(() => {
    loadMetric('week');
});

router.on('finish', () => {
    loadMetric(period.value);
})

const changeDate = (event: any) => {
    loadMetric(event);
}

const loadMetric = (period: string) => {
    axios.get(route('owner.doctor-workload'), {params: {period}}).then(({data}) => {
        data_keys.value = data.data_keys;
        data_values.value = data.data_values;
    })
}

const setChartData = () => {
    const documentStyle = getComputedStyle(document.documentElement);

    return {
        labels: data_keys,
        datasets: [
            {
                label: trans('Number of records'),
                backgroundColor: documentStyle.getPropertyValue('--p-cyan-500'),
                borderColor: documentStyle.getPropertyValue('--p-cyan-500'),
                data: data_values
            },
        ]
    };
};
const setChartOptions = () => {
    const documentStyle = getComputedStyle(document.documentElement);
    const textColor = documentStyle.getPropertyValue('--p-text-color');
    const textColorSecondary = documentStyle.getPropertyValue('--p-text-muted-color');
    const surfaceBorder = documentStyle.getPropertyValue('--p-content-border-color');

    return {
        indexAxis: 'y',
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
            legend: {
                labels: {
                    color: textColor
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: textColorSecondary,
                    font: {
                        weight: 500
                    }
                },
                grid: {
                    display: false,
                    drawBorder: false
                }
            },
            y: {
                ticks: {
                    color: textColorSecondary
                },
                grid: {
                    color: surfaceBorder,
                    drawBorder: false
                }
            }
        }
    };
}
const chartData = ref();
const chartOptions = ref();
chartData.value = setChartData();
chartOptions.value = setChartOptions();
</script>

<template>
    <div class="flex flex-col gap-4">
        <h2 class="font-medium text-gray-700">{{ trans('Doctors workload') }}</h2>
        <div class="w-[10rem]">
            <Select @update:model-value="changeDate" v-model="period" >
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
        <div>
            <Chart type="bar" :data="chartData" :options="chartOptions" class="h-[30rem]"  />
        </div>
    </div>
</template>