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
import DoctorAdd from '@/components/owner/DoctorAdd.vue';

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
            <div class="flex gap-4 items-center justify-between">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Doctors') }}</h2>
                </div>
                <div class="flex justify-end">
                    <DoctorAdd />
                </div>
            </div>
            <div class="overflow-x-auto max-w-full">
                <Table>
                    <TableCaption>{{trans('A list of doctors')}}</TableCaption>
                    <TableHeader>
                    <TableRow>
                        <TableHead>{{trans('Full name')}}</TableHead>
                        <TableHead>{{trans('Speciality')}}</TableHead>
                        <TableHead>{{trans('Joined at')}} </TableHead>
                    </TableRow>
                    </TableHeader>
                    <TableBody>
                    <TableRow v-for="doctor in doctors">
                        <TableCell>{{ doctor.full_name }}</TableCell>
                        <TableCell>{{ doctor.speciality }}</TableCell>
                        <TableCell>{{ doctor.joined_at }}</TableCell>
                    </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>