<script setup lang="ts">
import Paginator from '@/components/common/Paginator.vue';
import AppointmentTable from '@/components/owner/AppointmentTable.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    appointments: any;
    patients: any;
    doctors: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Appointments'),
        href: '/owner/appointments',
    },
];

</script>

<template>
    <Head :title="trans('Appointments')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Appointments') }}</h2>
                </div>
                <div class="flex justify-end">
                    <Button as-child>
                        <Link :href="route('owner.appointments.create')">{{ trans('Add appointment') }}</Link>
                    </Button>
                </div>
            </div>
            <div class="max-w-full overflow-x-auto">
                <AppointmentTable :appointments="appointments.data" />
            </div>
            <div class="flex justify-center">
                <Paginator :url="route('owner.appointments.index')" :items="appointments" />
            </div>
        </div>
    </AppLayout>
</template>
