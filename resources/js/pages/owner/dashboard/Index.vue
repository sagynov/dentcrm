<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

import DoctorWorkload from '@/components/owner/DoctorWorkload.vue';
import AppointmentMetric from '@/components/owner/AppointmentMetric.vue';

interface Props {
    
}

defineProps<Props>();


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Dashboard'),
        href: '/owner',
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
            <div>
                <DoctorWorkload />
            </div>
        </div>
    </AppLayout>
</template>