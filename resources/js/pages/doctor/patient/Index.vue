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


interface Props {
    patients: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Patients'),
        href: '/doctor/patients',
    },
];
</script>

<template>
    <Head :title="trans('Patients')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-4 items-center justify-between">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Patients') }}</h2>
                </div>
                <div class="flex justify-end">
                    
                </div>
            </div>
            <div class="overflow-x-auto max-w-full">
                <Table>
                    <TableCaption>{{trans('A list of patients')}}</TableCaption>
                    <TableHeader>
                    <TableRow>
                        <TableHead>{{trans('IIN')}}</TableHead>
                        <TableHead>{{trans('Full name')}}</TableHead>
                        <TableHead>{{trans('Birth date')}}</TableHead>
                        <TableHead>{{trans('Joined at')}} </TableHead>
                    </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="patient in patients">
                            <TableCell>{{ patient.iin }}</TableCell>
                            <TableCell>{{ patient.full_name }}</TableCell>
                            <TableCell>{{ patient.birth_date }}</TableCell>
                            <TableCell>{{ patient.joined_at }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>