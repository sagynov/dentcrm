<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { trans } from 'laravel-vue-i18n';
import AppointmentStatus from '../common/AppointmentStatus.vue';
import { Link } from '@inertiajs/vue3';

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
                <TableHead>{{ trans('Patient') }}</TableHead>
                <TableHead>{{ trans('Notes') }}</TableHead>
                <TableHead>{{ trans('Status') }}</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="appointment in appointments" :key="'appointment_' + appointment.id">
                <TableCell>{{ appointment.visit_at }}</TableCell>
                <TableCell>
                    <Link :href="route('doctor.patients.show', appointment.patient_id)" class="text-sky-600">
                        {{ appointment.patient }}
                    </Link>
                </TableCell>
                <TableCell>{{ appointment.notes }}</TableCell>
                <TableCell><AppointmentStatus :status="appointment.status" /> </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
