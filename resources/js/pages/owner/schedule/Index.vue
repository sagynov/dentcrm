<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';
import DatePicker from 'primevue/datepicker';
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
    axios
        .get(route('owner.schedules.get-schedule'), {
            params: { date },
        })
        .then(({ data }) => {
            doctors.value = data.doctors;
            hours.value = data.hours;
            appointments.value = data.appointments;
            datetime.value = data.datetime;
            now.value = data.now;
        });
};
</script>

<template>
    <Head :title="trans('Schedule')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-12">
                <div class="col-span-12 md:col-span-8">
                    <div class="rounded-lg border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[150px] border-r">{{ trans('Doctor') }}</TableHead>
                                    <TableHead v-for="period in periods" :key="'head_' + period" class="w-[50px] border-x">{{ period }}</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="doctor in doctors" class="h-[50px] border-collapse border" :key="'doctor_' + doctor.id">
                                    <TableCell class="h-[50px] border-collapse overflow-hidden border">{{ doctor.full_name }}</TableCell>
                                    <TableCell class="h-[50px] w-[50px] border-collapse border p-0" v-for="hour in hours" :key="'hour_' + hour">
                                        <div v-if="appointments[doctor.id][hour]" class="h-full w-full">
                                            <HoverCard>
                                                <HoverCardTrigger
                                                    :class="{
                                                        'bg-sky-600': appointments[doctor.id][hour][0].status == 'scheduled',
                                                        'bg-red-500': appointments[doctor.id][hour][0].status == 'canceled',
                                                        'bg-green-600': appointments[doctor.id][hour][0].status == 'completed',
                                                    }"
                                                    class="flex h-full w-full cursor-pointer items-center justify-center p-2 text-white"
                                                >
                                                    <div>{{ trans('' + appointments[doctor.id][hour][0].status) }}</div>
                                                </HoverCardTrigger>
                                                <HoverCardContent>
                                                    <div
                                                        v-for="appointment in appointments[doctor.id][hour]"
                                                        :key="'appointment_' + appointment.id"
                                                        class="mb-2 border-b py-2"
                                                    >
                                                        <div class="mb-1">{{ appointment.visit_at }}</div>
                                                        <div>{{ appointment.patient }}</div>
                                                        <div>{{ trans('' + appointment.status) }}</div>
                                                    </div>
                                                </HoverCardContent>
                                            </HoverCard>
                                        </div>
                                        <div class="h-full w-full bg-yellow-400 p-2 text-white" v-else>
                                            <HoverCard>
                                                <HoverCardTrigger class="flex h-full w-full cursor-pointer items-center justify-center">{{
                                                    trans('Empty')
                                                }}</HoverCardTrigger>
                                                <HoverCardContent>
                                                    <div class="mb-2 border-b py-2">{{ now[hour] }}</div>
                                                    <div class="mb-2 border-b py-2">{{ trans('Empty') }}</div>
                                                    <Button as-child>
                                                        <Link
                                                            :href="
                                                                route('owner.appointments.create') +
                                                                '?doctor=' +
                                                                doctor.id +
                                                                '&date=' +
                                                                datetime[hour].date +
                                                                '&time=' +
                                                                datetime[hour].time +
                                                                '&from=schedule'
                                                            "
                                                            >{{ trans('Add appointment') }}</Link
                                                        >
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
