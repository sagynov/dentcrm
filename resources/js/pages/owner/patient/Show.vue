<script setup lang="ts">
import PatientAdd from '@/components/owner/PatientAdd.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { CalendarDaysIcon, BuildingOfficeIcon, IdentificationIcon } from '@heroicons/vue/24/outline'
import Paginator from 'primevue/paginator';

interface Props {
    patient: any;
    records: any;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Patients'),
        href: '/owner/patients',
    },
    {
        title: trans('Patient'),
        href: route('owner.patients.show', props.patient.id),
    },
];

const setPage = (event: any) => {
    const page = event.page + 1;
    router.get(route('owner.patients.show', props.patient.id), {page}, {preserveState: true, preserveScroll: true});
}
</script>

<template>
    <Head :title="trans('Patients')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ patient.full_name }}</h2>
                </div>
                <div class="flex flex-col gap-2">
                    <div>
                        {{ trans('Birth date') }}: {{ patient.birth_date }}
                    </div>
                </div>
            </div>
            
            <div class="max-w-full">
                <div class="flex gap-4 items-center justify-between">
                    <div>
                        <h3 class="font-medium text-gray-700">{{ trans('Patient records') }}</h3>
                    </div>
                    <div class="flex justify-end">

                    </div>
                </div>
                <div class="flex flex-col gap-4 mt-4">
                    <div v-for="record in records.data" :key="'record_'+record.id" class="border p-4 rounded-lg flex flex-col gap-2">
                        <div class="flex items-center"><CalendarDaysIcon class="inline size-6 mr-2" /> {{ record.created_at }}</div>
                        <div>{{ record.comment }}</div>
                        <div class="flex items-center"><IdentificationIcon class="size-6 inline mr-2" /> {{ record.doctor }}</div>
                        <div class="flex items-center"><BuildingOfficeIcon class="size-6 inline mr-2" /> {{ record.clinic }}</div>
                    </div>
                </div>
            </div>
            <Paginator v-if="records.meta.total > 0" :rows="records.meta.per_page" :totalRecords="records.meta.total" @page="setPage"></Paginator>
        </div>
    </AppLayout>
</template>