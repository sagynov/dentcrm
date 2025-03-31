<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Button } from '@/components/ui/button';
import AppointmentAdd from '@/components/doctor/AppointmentAdd.vue';
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationLast,
  PaginationList,
  PaginationListItem,
  PaginationNext,
  PaginationPrev,
} from '@/components/ui/pagination'

interface Props {
    appointments: any;
    patients: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Appointments'),
        href: '/doctor/appointments',
    },
];
const setPage = (page: number) => {
    router.get(route('doctor.patients.index'), {page}, {preserveState: true, preserveScroll: true});
}
</script>

<template>
    <Head :title="trans('Appointments')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-4 items-center justify-between">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Appointments') }}</h2>
                </div>
                <div class="flex justify-end">
                    <AppointmentAdd :patients="patients" />
                </div>
            </div>
            <div class="overflow-x-auto max-w-full">
                <Table>
                    <TableCaption>{{trans('A list of appointments')}}</TableCaption>
                    <TableHeader>
                    <TableRow>
                        <TableHead>{{trans('Visit at')}}</TableHead>
                        <TableHead>{{trans('Patient')}}</TableHead>
                        <TableHead>{{trans('Notes')}}</TableHead>
                        <TableHead>{{trans('Status')}}</TableHead>
                    </TableRow>
                    </TableHeader>
                    <TableBody>
                    <TableRow v-for="appointment in appointments.data" :key="'appointment_'+appointment.id">
                        <TableCell>{{ appointment.visit_at }}</TableCell>
                        <TableCell>
                            <Link :href="route('doctor.patients.show', appointment.patient_id)" class="text-sky-600">
                                {{ appointment.patient }}
                            </Link>
                        </TableCell>
                        <TableCell>{{ appointment.notes }}</TableCell>
                        <TableCell>{{ trans(appointment.status) }}</TableCell>
                    </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex justify-center">
                <Pagination v-slot="{ page }" @update:page="setPage" :items-per-page="appointments.meta.per_page" :total="appointments.meta.total" :sibling-count="1" show-edges :default-page="appointments.meta.current_page">
                    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
                    <PaginationFirst />
                    <PaginationPrev />
    
                    <template v-for="(item, index) in items">
                        <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                        <Button class="w-9 h-9 p-0" :variant="item.value === page ? 'default' : 'outline'">
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