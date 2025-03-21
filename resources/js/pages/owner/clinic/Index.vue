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
import ClinicAdd from '@/components/owner/ClinicAdd.vue';

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
            <div class="flex gap-4 items-center justify-between">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('My clinics') }}</h2>
                </div>
                <div class="flex justify-end">
                    <ClinicAdd />
                </div>
            </div>
            <div class="overflow-x-auto max-w-full">
                <Table>
                    <TableCaption>{{trans('A list of your clinics')}}</TableCaption>
                    <TableHeader>
                    <TableRow>
                        <TableHead>{{trans('Name')}}</TableHead>
                        <TableHead>{{trans('Specialization')}}</TableHead>
                        <TableHead>{{trans('Address')}}</TableHead>
                        <TableHead>{{trans('Phone')}}</TableHead>
                    </TableRow>
                    </TableHeader>
                    <TableBody>
                    <TableRow v-for="clinic in clinics">
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