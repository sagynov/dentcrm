<script setup lang="ts">
import DoctorAdd from '@/components/owner/DoctorAdd.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

import Paginator from '@/components/common/Paginator.vue';

interface Props {
    doctors: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Doctors'),
        href: '/doctors',
    },
];
</script>

<template>
    <Head :title="trans('Doctors')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Doctors') }}</h2>
                </div>
                <div class="flex justify-end">
                    <DoctorAdd />
                </div>
            </div>
            <div class="max-w-full overflow-x-auto">
                <Table>
                    <TableCaption>{{ trans('A list of doctors') }}</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ trans('Full name') }}</TableHead>
                            <TableHead>{{ trans('Speciality') }}</TableHead>
                            <TableHead>{{ trans('Joined at') }} </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="doctor in doctors.data" :key="'doctor_' + doctor.id">
                            <TableCell>{{ doctor.full_name }}</TableCell>
                            <TableCell>{{ trans('' + doctor.speciality) }}</TableCell>
                            <TableCell>{{ doctor.joined_at }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex justify-center">
                <Paginator :url="route('owner.doctors.index')" :items="doctors" />
            </div>
        </div>
    </AppLayout>
</template>
