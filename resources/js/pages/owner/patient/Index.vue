<script setup lang="ts">
import PatientAdd from '@/components/owner/PatientAdd.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

interface Props {
    patients: any;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Patients'),
        href: '/owner/patients',
    },
];
</script>

<template>
    <Head :title="trans('Patients')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-4 items-center justify-between">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Patients') }}</h2>
                </div>
                <div class="flex justify-end">
                    <PatientAdd />
                </div>
            </div>
            <div class="w-full">
                <DataTable :value="patients" tableStyle="min-width: 50rem">
                    <Column field="iin" :header="trans('IIN')"></Column>
                    <Column :header="trans('Full name')">
                        <template #body="slotProps">
                            <Link :href="route('owner.patients.show', slotProps.data.id)" class="text-sky-600">
                                {{ slotProps.data.full_name }}
                            </Link>
                        </template>
                    </Column>
                    <Column field="birth_date" :header="trans('Birth date')"></Column>
                    <Column field="joined_at" :header="trans('Joined at')"></Column>
                </DataTable>
            </div>
        </div>
    </AppLayout>
</template>