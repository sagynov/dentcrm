<script setup lang="ts">
import Paginator from '@/components/common/Paginator.vue';
import ServiceAction from '@/components/owner/ServiceAction.vue';
import ServiceAdd from '@/components/owner/ServiceAdd.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    services: any;
    clinic_services: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Services'),
        href: '/owner/services',
    },
];
</script>

<template>
    <Head :title="trans('Services')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Services') }}</h2>
                </div>
                <div class="flex justify-end">
                    <ServiceAdd :clinic_services="clinic_services" />
                </div>
            </div>
            <div class="max-w-full overflow-x-auto">
                <Table>
                    <TableCaption>{{ trans('A list of services') }}</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ trans('Service name') }}</TableHead>
                            <TableHead>{{ trans('Description') }}</TableHead>
                            <TableHead>{{ trans('Price') }} </TableHead>
                            <TableHead>{{ trans('Patient') }}</TableHead>
                            <TableHead>{{ trans('Doctor') }}</TableHead>
                            <TableHead>{{ trans('Action') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="service in services.data" :key="'service_' + service.id">
                            <TableCell>{{ service.name }}</TableCell>
                            <TableCell>{{ service.description }}</TableCell>
                            <TableCell>{{ service.price_format }}</TableCell>
                            <TableCell>
                                <Link :href="route('owner.patients.show', service.patient_id)" class="text-sky-600">
                                    {{ service.patient.full_name }}
                                </Link>
                            </TableCell>
                            <TableCell>{{ service.doctor.full_name }}</TableCell>
                            <TableCell class="flex justify-center">
                                <ServiceAction :service="service" />
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex justify-center">
                <Paginator :url="route('owner.services.index')" :items="services" />
            </div>
        </div>
    </AppLayout>
</template>
