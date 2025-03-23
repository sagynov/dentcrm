<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Button } from '@/components/ui/button';
import AppointmentAdd from '@/components/doctor/AppointmentAdd.vue';

interface Props {
    appointments: any;
    patients: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Appointments'),
        href: '/doctor/appointments',
    },
];
</script>

<template>
    <Head :title="trans('Appointments')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-4 items-center justify-between">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Appointments') }}</h2>
                </div>
                <div class="flex justify-end">
                    <AppointmentAdd :patients="patients" />
                </div>
            </div>
            <div class="overflow-x-auto max-w-full">
                <Table>
                    <TableCaption>{{trans('A list of appointments')}}</TableCaption>
                    <TableHeader>
                    <TableRow>
                        <TableHead>{{trans('Visit at')}}</TableHead>
                        <TableHead>{{trans('Patient')}}</TableHead>
                        <TableHead>{{trans('Notes')}}</TableHead>
                        <TableHead>{{trans('Status')}}</TableHead>
                    </TableRow>
                    </TableHeader>
                    <TableBody>
                    <TableRow v-for="appointment in appointments" :key="'appointment_'+appointment.id">
                        <TableCell>{{ appointment.visit_at }}</TableCell>
                        <TableCell>{{ appointment.patient }}</TableCell>
                        <TableCell>{{ appointment.notes }}</TableCell>
                        <TableCell>{{ trans(appointment.status) }}</TableCell>
                    </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>