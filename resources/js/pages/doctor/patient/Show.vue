<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

import PatientRecordAdd from '@/components/doctor/PatientRecordAdd.vue';
import { Button } from '@/components/ui/button';
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/components/ui/pagination';
import { BuildingIcon, CalendarDaysIcon, PaperclipIcon, UserIcon } from 'lucide-vue-next';

interface Props {
    patient: any;
    records: any;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Patients'),
        href: '/doctor/patients',
    },
    {
        title: trans('Patient'),
        href: route('doctor.patients.show', props.patient.id),
    },
];

const setPage = (event: any) => {
    const page = event.page + 1;
    router.get(route('doctor.patients.show', props.patient.id), { page }, { preserveState: true, preserveScroll: true });
};
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
                    <div>{{ trans('Birth date') }}: {{ patient.birth_date }}</div>
                </div>
            </div>
            <div class="max-w-full">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-medium text-gray-700">{{ trans('Patient records') }}</h3>
                    </div>
                    <div class="flex justify-end">
                        <PatientRecordAdd :patient="patient" />
                    </div>
                </div>
                <div class="mt-4 flex flex-col gap-4">
                    <div v-for="record in records.data" :key="'record_' + record.id" class="flex flex-col gap-2 rounded-lg border p-4">
                        <div class="flex items-center"><CalendarDaysIcon class="mr-2" /> {{ record.created_at }}</div>
                        <div class="flex flex-col gap-4 border-b p-4">
                            <div>{{ record.comment }}</div>
                            <div class="flex flex-col gap-2">
                                <div
                                    v-for="(attachment, index) in record.attachments"
                                    :key="'patient_' + record.id + '_attachment_' + index"
                                    class="flex"
                                >
                                    <PaperclipIcon class="mr-2" />
                                    <a :href="route('download', patient.id) + '?path=' + attachment.path" class="mr-2 text-sky-600">{{
                                        attachment.name
                                    }}</a>
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
                <Pagination
                    v-slot="{ page }"
                    @update:page="setPage"
                    :items-per-page="records.meta.per_page"
                    :total="records.meta.total"
                    :sibling-count="1"
                    show-edges
                    :default-page="records.meta.current_page"
                >
                    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
                        <PaginationFirst />
                        <PaginationPrev />

                        <template v-for="(item, index) in items">
                            <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                                <Button class="h-9 w-9 p-0" :variant="item.value === page ? 'default' : 'outline'">
                                    {{ item.value }}
                                </Button>
                            </PaginationListItem>
                            <PaginationEllipsis v-else :key="item.type" :index="index" />
                        </template>

                        <PaginationNext />
                        <PaginationLast />
                    </PaginationList>
                </Pagination>
            </div>
        </div>
    </AppLayout>
</template>
