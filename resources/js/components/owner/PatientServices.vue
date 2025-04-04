<script setup lang="ts">
import { trans } from 'laravel-vue-i18n';
import Paginator from '../common/Paginator.vue';
import ServiceTable from './ServiceTable.vue';

interface Props {
    patient: any;
    services: any;
}
defineProps<Props>();
</script>
<template>
    <div class="my-4 max-w-full">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="font-medium text-gray-700">{{ trans('Patient services') }}</h3>
            </div>
            <div class="flex justify-end"></div>
        </div>
        <div class="max-w-full overflow-x-auto">
            <ServiceTable :services="services.data" />
        </div>
    </div>
    <div class="flex justify-center">
        <Paginator :url="route('owner.patients.services', patient.id)" :items="services" />
    </div>
</template>
