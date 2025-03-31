<script setup lang="ts">
import AppointmentForm from '@/components/doctor/AppointmentForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    date?: any;
    time?: any;
    from?: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Appointments'),
        href: '/doctor/appointments',
    },
    {
        title: trans('Add appointment'),
        href: route('doctor.appointments.create'),
    },
];
</script>

<template>
    <Head :title="trans('Appointments')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <AppointmentForm :date="date" :time="time" :from="from" />
        </div>
    </AppLayout>
</template>
