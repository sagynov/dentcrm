<script setup lang="ts">
import { trans } from 'laravel-vue-i18n';
import { BuildingIcon, CalendarDaysIcon, PaperclipIcon, UserIcon } from 'lucide-vue-next';
import Paginator from '../common/Paginator.vue';

interface Props {
    patient: any;
    records: any;
}
defineProps<Props>();
</script>
<template>
    <div class="my-4 max-w-full">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="font-medium text-gray-700">{{ trans('Patient records') }}</h3>
            </div>
            <div class="flex justify-end"></div>
        </div>
        <div class="mt-4 flex flex-col gap-4">
            <div v-for="record in records.data" :key="'record_' + record.id" class="flex flex-col gap-2 rounded-lg border p-4">
                <div class="flex items-center"><CalendarDaysIcon class="mr-2" /> {{ record.created_at }}</div>
                <div class="flex flex-col gap-4 border-b p-4">
                    <div>{{ record.comment }}</div>
                    <div class="flex flex-col gap-2">
                        <div v-for="(attachment, index) in record.attachments" :key="'patient_' + record.id + '_attachment_' + index" class="flex">
                            <PaperclipIcon class="mr-2" />
                            <a :href="route('download', patient.id) + '?path=' + attachment.path" class="mr-2 text-sky-600">{{ attachment.name }}</a>
                            <span>{{ '(' + attachment.size + ')' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center"><UserIcon class="mr-2" /> {{ record.doctor }}</div>
                <div class="flex items-center"><BuildingIcon class="mr-2" /> {{ record.clinic }}</div>
                <div class="jusify-end"></div>
            </div>
        </div>
    </div>
    <div class="flex justify-center">
        <Paginator :url="route('owner.patients.show', patient.id)" :items="records" />
    </div>
</template>
