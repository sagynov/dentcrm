<script setup lang="ts">
import DepositForm from '@/components/owner/DepositForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    patient?: any;
    service?: any;
    from?: any;
}
defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Patient payments'),
        href: '/owner/deposits',
    },
    {
        title: trans('Add deposit'),
        href: route('owner.deposits.create'),
    },
];
</script>

<template>
    <Head :title="trans('Appointments')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <DepositForm :patient="patient" :service="service" :from="from" />
        </div>
    </AppLayout>
</template>
