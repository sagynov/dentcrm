<script setup lang="ts">
import Paginator from '@/components/common/Paginator.vue';
import ServiceAdd from '@/components/owner/ServiceAdd.vue';
import ServiceTable from '@/components/owner/ServiceTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    services: any;
    clinic_services: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Services'),
        href: '/owner/services',
    },
];
</script>

<template>
    <Head :title="trans('Services')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Services') }}</h2>
                </div>
                <div class="flex justify-end">
                    <ServiceAdd :clinic_services="clinic_services" />
                </div>
            </div>
            <div class="max-w-full overflow-x-auto">
                <ServiceTable :services="services.data" />
            </div>
            <div class="flex justify-center">
                <Paginator :url="route('owner.services.index')" :items="services" />
            </div>
        </div>
    </AppLayout>
</template>
