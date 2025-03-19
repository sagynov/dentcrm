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
import AppointmentAdd from '@/components/owner/AppointmentAdd.vue';

interface Props {
    appointments: any;
    patients: any;
    doctors: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Appointments'),
        href: '/owner/appointments',
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
                    <AppointmentAdd :doctors="doctors" :patients="patients" />
                </div>
            </div>
            <div class="overflow-x-auto max-w-full">
                <Table>
                    <TableCaption>A list of your appointments</TableCaption>
                    <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Specialization</TableHead>
                        <TableHead>Address</TableHead>
                        <TableHead>Phone</TableHead>
                    </TableRow>
                    </TableHeader>
                    <TableBody>
                    <TableRow v-for="clinic in appointments">
                        <TableCell>{{ clinic.name }}</TableCell>
                        <TableCell>{{ clinic.specialization }}</TableCell>
                        <TableCell>{{ clinic.address }}</TableCell>
                        <TableCell>{{ clinic.phone }}</TableCell>
                    </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>