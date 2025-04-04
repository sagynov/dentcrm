<script setup lang="ts">
import PatientAppointments from '@/components/owner/PatientAppointments.vue';
import PatientDeposits from '@/components/owner/PatientDeposits.vue';
import PatientRecords from '@/components/owner/PatientRecords.vue';
import PatientServices from '@/components/owner/PatientServices.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    patient: any;
    records?: any;
    deposits?: any;
    services?: any;
    appointments?: any;
}

const props = defineProps<Props>();

const defaultValue = props.records ? 'records' : props.deposits ? 'deposits' : props.services ? 'services' : props.appointments && 'appointments';

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
const setTab = (event: any) => {
    if (event == 'records') {
        router.visit(route('owner.patients.show', props.patient.id));
    }
    if (event == 'appointments') {
        router.visit(route('owner.patients.appointments', props.patient.id));
    }
    if (event == 'services') {
        router.visit(route('owner.patients.services', props.patient.id));
    }
    if (event == 'deposits') {
        router.visit(route('owner.patients.deposits', props.patient.id));
    }
};
</script>

<template>
    <Head :title="patient.full_name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex flex-col gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ patient.full_name }}</h2>
                </div>
                <div class="flex flex-col gap-2">
                    <div>{{ trans('Birth date') }}: {{ patient.birth_date }}</div>
                    <div>{{ trans('Phone') }}: {{ patient.phone }}</div>
                </div>
            </div>
            <Tabs :default-value="defaultValue" @update:model-value="setTab">
                <TabsList class="grid w-[600px] grid-cols-4">
                    <TabsTrigger value="records">
                        {{ trans('Patient records') }}
                    </TabsTrigger>
                    <TabsTrigger value="deposits">
                        {{ trans('Patient deposits') }}
                    </TabsTrigger>
                    <TabsTrigger value="services">
                        {{ trans('Patient services') }}
                    </TabsTrigger>
                    <TabsTrigger value="appointments">
                        {{ trans('Patient appointments') }}
                    </TabsTrigger>
                </TabsList>
                <TabsContent value="records">
                    <PatientRecords v-if="records" :patient="patient" :records="records" />
                </TabsContent>
                <TabsContent value="deposits">
                    <PatientDeposits v-if="deposits" :patient="patient" :deposits="deposits" />
                </TabsContent>
                <TabsContent value="services">
                    <PatientServices v-if="services" :patient="patient" :services="services" />
                </TabsContent>
                <TabsContent value="appointments">
                    <PatientAppointments v-if="appointments" :patient="patient" :appointments="appointments" />
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
