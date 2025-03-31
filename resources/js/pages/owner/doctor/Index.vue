<script setup lang="ts">
import DoctorAdd from '@/components/owner/DoctorAdd.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

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

interface Props {
    doctors: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Doctors'),
        href: '/doctors',
    },
];
const setPage = (page: number) => {
    router.get(route('owner.doctors.index'), { page }, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <Head :title="trans('Doctors')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Doctors') }}</h2>
                </div>
                <div class="flex justify-end">
                    <DoctorAdd />
                </div>
            </div>
            <div class="max-w-full overflow-x-auto">
                <Table>
                    <TableCaption>{{ trans('A list of doctors') }}</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ trans('Full name') }}</TableHead>
                            <TableHead>{{ trans('Speciality') }}</TableHead>
                            <TableHead>{{ trans('Joined at') }} </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="doctor in doctors.data" :key="'doctor_' + doctor.id">
                            <TableCell>{{ doctor.full_name }}</TableCell>
                            <TableCell>{{ trans('' + doctor.speciality) }}</TableCell>
                            <TableCell>{{ doctor.joined_at }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex justify-center">
                <Pagination
                    v-slot="{ page }"
                    @update:page="setPage"
                    :items-per-page="doctors.meta.per_page"
                    :total="doctors.meta.total"
                    :sibling-count="1"
                    show-edges
                    :default-page="doctors.meta.current_page"
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
