<script setup lang="ts">
import Paginator from '@/components/common/Paginator.vue';
import PatientSearch from '@/components/common/PatientSearch.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

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

const onSelect = (patient: any) => {
    router.visit(route('doctor.patients.show', patient.id));
};
</script>

<template>
    <Head :title="trans('Patients')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Patients') }}</h2>
                </div>
                <div class="flex justify-end"></div>
            </div>
            <div class="mb-4">
                <PatientSearch @on-select="onSelect" />
            </div>
            <div class="max-w-full overflow-x-auto">
                <Table>
                    <TableCaption>{{ trans('A list of patients') }}</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ trans('IIN') }}</TableHead>
                            <TableHead>{{ trans('Full name') }}</TableHead>
                            <TableHead>{{ trans('Birth date') }}</TableHead>
                            <TableHead>{{ trans('Joined at') }} </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="patient in patients.data" :key="'patient_' + patient.id">
                            <TableCell>{{ patient.iin }}</TableCell>
                            <TableCell>
                                <Link :href="route('doctor.patients.show', patient.id)" class="text-sky-600">
                                    {{ patient.full_name }}
                                </Link>
                            </TableCell>
                            <TableCell>{{ patient.birth_date }}</TableCell>
                            <TableCell>{{ patient.joined_at }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex justify-center">
                <Paginator :url="route('doctor.patients.index')" :items="patients" />
            </div>
        </div>
    </AppLayout>
</template>
