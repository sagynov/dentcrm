<script setup lang="ts">
import ServiceStatus from '@/components/common/ServiceStatus.vue';
import ServiceAction from '@/components/owner/ServiceAction.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    services: any;
}

defineProps<Props>();
</script>

<template>
    <Table>
        <TableCaption>{{ trans('A list of services') }}</TableCaption>
        <TableHeader>
            <TableRow>
                <TableHead>{{ trans('Service name') }}</TableHead>
                <TableHead>{{ trans('Description') }}</TableHead>
                <TableHead>{{ trans('Price') }} </TableHead>
                <TableHead>{{ trans('Paid') }} </TableHead>
                <TableHead>{{ trans('Patient') }}</TableHead>
                <TableHead>{{ trans('Doctor') }}</TableHead>
                <TableHead>{{ trans('Status') }}</TableHead>
                <TableHead>{{ trans('Action') }}</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="service in services" :key="'service_' + service.id">
                <TableCell>{{ service.name }}</TableCell>
                <TableCell>{{ service.description }}</TableCell>
                <TableCell>{{ service.price_format }}</TableCell>
                <TableCell>{{ service.paid_format }}</TableCell>
                <TableCell>
                    <Link :href="route('owner.patients.show', service.patient_id)" class="text-sky-600">
                        {{ service.patient.full_name }}
                    </Link>
                </TableCell>
                <TableCell>{{ service.doctor.full_name }}</TableCell>
                <TableCell>
                    <ServiceStatus :status="service.status" />
                </TableCell>
                <TableCell class="flex justify-center">
                    <ServiceAction :service="service" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
