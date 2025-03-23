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
import { Head } from '@inertiajs/vue3';
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
    hours: any;
}


const props = defineProps<Props>();

const doctors = ref(props.doctors);
const hours = ref(props.hours);

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
                                    <TableHead class="border-r w-[150px]">{{ trans('Cabinet') }}</TableHead>
                                    <TableHead v-for="hour in hours" :key="'head_'+hour" class="border-x w-[50px]">{{ hour }}</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="doctor in doctors" class="border border-collapse h-[50px]" :key="'doctor_'+doctor.id">
                                    <TableCell class="border border-collapse h-[50px] overflow-hidden">{{ doctor.full_name }}</TableCell>
                                    <TableCell class="border border-collapse p-0 h-[50px] w-[50px] bg-gray-400" v-for="hour in hours" :key="'cell_'+hour">
                                        <div  v-if="doctor.appointments.length > 0">
                                            <div v-for="appointment in doctor.appointments" :key="'appointment_'+appointment.id" class="w-full h-full">
                                                <div v-if="appointment.visit_hour == hour" class="bg-green-600 w-full h-full text-white">
                                                    <HoverCard>
                                                        <HoverCardTrigger class="w-full h-full flex items-center justify-center"></HoverCardTrigger>
                                                        <HoverCardContent>
                                                            <div>{{ appointment.patient }}</div>
                                                            <div>{{ appointment.visit_at }}</div>
                                                        </HoverCardContent>
                                                    </HoverCard>
                                                </div>
                                                <div class="bg-gray-400  w-full h-full text-white" v-else>
                                                    <HoverCard>
                                                        <HoverCardTrigger class="w-full h-full flex items-center justify-center"></HoverCardTrigger>
                                                        <HoverCardContent>
                                                            <Button variant="secondary">Add appointment</Button>
                                                        </HoverCardContent>
                                                    </HoverCard>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-400 w-full h-full text-white" v-else>
                                            <HoverCard>
                                                <HoverCardTrigger class="w-full h-full flex items-center justify-center"></HoverCardTrigger>
                                                <HoverCardContent>
                                                    <Button variant="secondary">Add appointment</Button>
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
