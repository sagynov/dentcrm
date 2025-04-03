<script setup lang="ts">
import ClinicServices from '@/components/owner/ClinicServices.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { MapPinIcon, PhoneIcon } from 'lucide-vue-next';

interface Props {
    clinic: any;
    services: any;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('My clinics'),
        href: '/owner/clinics',
    },
    {
        title: props.clinic.name,
        href: route('owner.clinics.show', props.clinic.id),
    },
];
</script>
<template>
    <Head :title="clinic.name" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ clinic.name }}</h2>
                </div>
                <div class="flex flex-col gap-4">
                    <div class="flex items-center"><MapPinIcon class="mr-2 inline" /> {{ clinic.address }}</div>
                    <div class="flex items-center"><PhoneIcon class="mr-2 inline" /> {{ clinic.phone }}</div>
                </div>
                <div class="flex flex-col gap-2">
                    <div>{{ trans('Created at') }}: {{ clinic.created_at }}</div>
                </div>
            </div>
            <ClinicServices :clinic="clinic" :services="services" />
        </div>
    </AppLayout>
</template>
