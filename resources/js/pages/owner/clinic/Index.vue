<script setup lang="ts">
import Paginator from '@/components/common/Paginator.vue';
import ClinicAdd from '@/components/owner/ClinicAdd.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    clinics: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('My clinics'),
        href: '/owner/clinics',
    },
];
</script>

<template>
    <Head :title="trans('My clinics')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('My clinics') }}</h2>
                </div>
                <div class="flex justify-end">
                    <ClinicAdd />
                </div>
            </div>
            <div class="max-w-full overflow-x-auto">
                <Table>
                    <TableCaption>{{ trans('A list of your clinics') }}</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ trans('Clinic name') }}</TableHead>
                            <TableHead>{{ trans('Specialization') }}</TableHead>
                            <TableHead>{{ trans('Address') }}</TableHead>
                            <TableHead>{{ trans('Phone') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="clinic in clinics.data" :key="'clinic_' + clinic.id">
                            <TableCell>{{ clinic.name }}</TableCell>
                            <TableCell>{{ trans('' + clinic.specialization) }}</TableCell>
                            <TableCell>{{ clinic.address }}</TableCell>
                            <TableCell>{{ clinic.phone }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex justify-center">
                <Paginator :url="route('owner.clinics.index')" :items="clinics" />
            </div>
        </div>
    </AppLayout>
</template>
