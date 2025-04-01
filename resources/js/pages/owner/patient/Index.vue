<script setup lang="ts">
import PatientAdd from '@/components/owner/PatientAdd.vue';
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
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    patients: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Patients'),
        href: '/owner/patients',
    },
];

const setPage = (page: number) => {
    router.get(route('owner.patients.index'), { page }, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <Head :title="trans('Patients')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Patients') }}</h2>
                </div>
                <div class="flex justify-end">
                    <PatientAdd />
                </div>
            </div>
            <div class="max-w-full overflow-x-auto">
                <Table>
                    <TableCaption>{{ trans('A list of patients') }}</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ trans('IIN') }}</TableHead>
                            <TableHead>{{ trans('Full name') }}</TableHead>
                            <TableHead>{{ trans('Birth date') }}</TableHead>
                            <TableHead>{{ trans('Joined at') }} </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="patient in patients.data" :key="'patient_' + patient.id">
                            <TableCell>{{ patient.iin }}</TableCell>
                            <TableCell>
                                <Link :href="route('owner.patients.show', patient.id)" class="text-sky-600">
                                    {{ patient.full_name }}
                                </Link>
                            </TableCell>
                            <TableCell>{{ patient.birth_date }}</TableCell>
                            <TableCell>{{ patient.joined_at }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex justify-center" v-if="patients.length > 0">
                <Pagination
                    v-slot="{ page }"
                    @update:page="setPage"
                    :items-per-page="patients.meta.per_page"
                    :total="patients.meta.total"
                    :sibling-count="1"
                    show-edges
                    :default-page="patients.meta.current_page"
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
