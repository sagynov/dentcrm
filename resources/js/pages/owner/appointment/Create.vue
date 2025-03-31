<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import AppointmentForm from '@/components/owner/AppointmentForm.vue';

interface Props {
    date?: any;
    time?: any;
    doctor?: any;
    from?: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Appointments'),
        href: '/owner/appointments',
    },
    {
        title: trans('Add appointment'),
        href: route('owner.appointments.create'),
    },
];
</script>

<template>
    <Head :title="trans('Appointments')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <AppointmentForm :date="date" :time="time" :doctor="doctor" :from="from" />
        </div>
    </AppLayout>
</template>