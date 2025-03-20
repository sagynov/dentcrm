<script setup lang="ts">
import { Calendar } from '@/components/ui/calendar';
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

interface Props {
    doctors: any;
    hours: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Schedule'),
        href: '/schedule',
    },
];
const days = ['06', '07', '08', '09', '10', '11', '12'];
const schedules = [
  {
    cabinet: 'Кабинет 1',
    patient: 'Ержан Серікбай 10:00',
    s_hours: [10, 12, 16]
  },
  {
    cabinet: 'Кабинет 2',
    patient: 'Ержан Серікбай 10:00',
    s_hours: [11, 13]
  },
  {
    cabinet: 'Кабинет 3',
    patient: 'Ержан Серікбай 10:00',
    s_hours: [10, 15, 18]
  },
];
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
                                <TableRow v-for="doctor in doctors" class="border border-collapse">
                                    <TableCell class="border border-collapse">{{ doctor.full_name }}</TableCell>
                                    <TableCell class="border border-collapse p-0" v-for="hour in hours" :key="'cell_'+hour">
                                        <div v-for="appointment in doctor.appointments" class="w-full h-full">
                                            <div v-if="appointment.visit_hour == hour" class="bg-green-600 w-full h-full p-4 text-center text-white">
                                                {{ appointment.patient }}
                                            </div>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div>
                        <Calendar :weekday-format="'short'" class="rounded-md border" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
