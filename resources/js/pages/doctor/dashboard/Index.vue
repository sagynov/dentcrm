<script setup lang="ts">
import AppointmentMetric from '@/components/doctor/AppointmentMetric.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Dashboard'),
        href: '/doctor',
    },
];
</script>

<template>
    <Head :title="trans('Dashboard')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-10 rounded-xl p-4">
            <div>
                <AppointmentMetric />
            </div>
        </div>
    </AppLayout>
</template>
