<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import {
  HoverCard,
  HoverCardContent,
  HoverCardTrigger,
} from '@/components/ui/hover-card'
import Button from '@/components/ui/button/Button.vue';
import DatePicker from 'primevue/datepicker';
import axios from 'axios';
import { ref } from 'vue';

interface Props {
    doctors: any;
    periods: any;
    hours: any;
    appointments: any;
    datetime: any;
    now: any;
}


const props = defineProps<Props>();

const doctors = ref(props.doctors);
const hours = ref(props.hours);
const appointments = ref(props.appointments);
const datetime = ref(props.datetime);
const now = ref(props.now);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Schedule'),
        href: '/schedule',
    },
];

const today = new Date();

const dateSelected = (date: any) => {
    axios.get(route('owner.schedules.get-schedule'), {
        params: {date}
    }).then(({data}) => {
        doctors.value = data.doctors;
        hours.value = data.hours;
        appointments.value = data.appointments;
        datetime.value = data.datetime;
        now.value = data.now;
    });
}
</script>

<template>
    <Head :title="trans('Schedule')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-12">
                <div class="col-span-12 md:col-span-8">
                    <div class="border rounded-lg">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="border-r w-[150px]">{{ trans('Doctor') }}</TableHead>
                                    <TableHead v-for="period in periods" :key="'head_'+period" class="border-x w-[50px]">{{ period }}</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="doctor in doctors" class="border border-collapse h-[50px]" :key="'doctor_'+doctor.id">
                                    <TableCell class="border border-collapse h-[50px] overflow-hidden">{{ doctor.full_name }}</TableCell>
                                    <TableCell class="border border-collapse p-0 h-[50px] w-[50px]" v-for="hour in hours" :key="'hour_'+hour">
                                        <div v-if="appointments[doctor.id][hour]" class="bg-green-600 p-2 w-full h-full text-white">
                                            <HoverCard>
                                                <HoverCardTrigger class="w-full h-full flex items-center justify-center cursor-pointer">
                                                    <div>{{ trans('scheduled') }}</div>
                                                </HoverCardTrigger>
                                                <HoverCardContent>
                                                    <div class="border-b py-2 mb-2">{{ appointments[doctor.id][hour].visit_at }}</div>
                                                    <div>{{ appointments[doctor.id][hour].patient }}</div>
                                                </HoverCardContent>
                                            </HoverCard>
                                        </div>
                                        <div class="bg-yellow-400 p-2 w-full h-full text-white" v-else>
                                            <HoverCard>
                                                <HoverCardTrigger class="w-full h-full flex items-center justify-center cursor-pointer">{{ trans('Empty') }}</HoverCardTrigger>
                                                <HoverCardContent>
                                                    <div class="border-b py-2 mb-2">{{ now[hour] }}</div>
                                                    <div class="border-b py-2 mb-2">{{ trans('Empty') }}</div>
                                                    <Button as-child>
                                                        <Link :href="route('owner.appointments.create')+'?doctor='+doctor.id+'&date='+datetime[hour].date+'&time='+datetime[hour].time+'&from=schedule'">{{ trans('Add appointment') }}</Link>
                                                    </Button>
                                                </HoverCardContent>
                                            </HoverCard>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div>
                        <DatePicker date-format="d-m-Y" v-model="today" @value-change="dateSelected" inline />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
