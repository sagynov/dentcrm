<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import AppointmentStatus from '../common/AppointmentStatus.vue';
import AppointmentAction from './AppointmentAction.vue';

interface Props {
    appointments: any;
}
defineProps<Props>();
</script>

<template>
    <Table>
        <TableCaption>{{ trans('A list of appointments') }}</TableCaption>
        <TableHeader>
            <TableRow>
                <TableHead>{{ trans('Visit at') }}</TableHead>
                <TableHead>{{ trans('Service') }}</TableHead>
                <TableHead>{{ trans('Patient') }}</TableHead>
                <TableHead>{{ trans('Doctor') }}</TableHead>
                <TableHead>{{ trans('Notes') }}</TableHead>
                <TableHead>{{ trans('Status') }}</TableHead>
                <TableHead>{{ trans('Action') }}</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="appointment in appointments" :key="'appointment_' + appointment.id">
                <TableCell>{{ appointment.visit_at }}</TableCell>
                <TableCell>{{ appointment.service }}</TableCell>
                <TableCell>
                    <Link :href="route('owner.patients.show', appointment.patient_id)" class="text-sky-600">
                        {{ appointment.patient }}
                    </Link>
                </TableCell>
                <TableCell>{{ appointment.doctor }}</TableCell>
                <TableCell class="max-w-[15rem]">{{ appointment.notes }}</TableCell>
                <TableCell><AppointmentStatus :status="appointment.status" /> </TableCell>
                <TableCell class="flex justify-center">
                    <AppointmentAction :appointment="appointment" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
