<script setup lang="ts">
import { trans } from 'laravel-vue-i18n';
import Paginator from '../common/Paginator.vue';
import ClinicServiceAdd from './ClinicServiceAdd.vue';
import ClinicServiceTable from './ClinicServiceTable.vue';

interface Props {
    clinic: any;
    services: any;
}
defineProps<Props>();
</script>
<template>
    <div class="my-4 max-w-full">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="font-medium text-gray-700">{{ trans('Clinic services') }}</h3>
            </div>
            <div class="flex justify-end">
                <ClinicServiceAdd :clinic="clinic" />
            </div>
        </div>
        <div class="max-w-full overflow-x-auto">
            <ClinicServiceTable :services="services.data" />
        </div>
    </div>
    <div class="flex justify-center">
        <Paginator :url="route('owner.clinics.index')" :items="services" />
    </div>
</template>
