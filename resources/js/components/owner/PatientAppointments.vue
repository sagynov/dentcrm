<script setup lang="ts">
import { trans } from 'laravel-vue-i18n';
import AppointmentTable from '../common/AppointmentTable.vue';
import Paginator from '../common/Paginator.vue';

interface Props {
    patient: any;
    appointments: any;
}
defineProps<Props>();
</script>
<template>
    <div class="my-4 max-w-full">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="font-medium text-gray-700">{{ trans('Patient appointments') }}</h3>
            </div>
            <div class="flex justify-end"></div>
        </div>
        <div class="max-w-full overflow-x-auto">
            <AppointmentTable :appointments="appointments.data" />
        </div>
    </div>
    <div class="flex justify-center">
        <Paginator :url="route('owner.patients.appointments', patient.id)" :items="appointments" />
    </div>
</template>
