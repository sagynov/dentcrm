<script setup lang="ts">
import Paginator from '@/components/common/Paginator.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    deposits: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Patient payments'),
        href: '/owner/deposits',
    },
];
</script>

<template>
    <Head :title="trans('Patient payments')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Patient payments') }}</h2>
                </div>
                <div class="flex justify-end">
                    <Button as-child>
                        <Link :href="route('owner.deposits.create')">{{ trans('Add deposit') }}</Link>
                    </Button>
                </div>
            </div>
            <div class="max-w-full overflow-x-auto">
                <Table>
                    <TableCaption>{{ trans('A list of payments') }}</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ trans('Paid on') }}</TableHead>
                            <TableHead>{{ trans('Amount') }}</TableHead>
                            <TableHead>{{ trans('Patient') }}</TableHead>
                            <TableHead>{{ trans('Service name') }}</TableHead>
                            <TableHead>{{ trans('Payment method') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="deposit in deposits.data" :key="'deposit_' + deposit.id">
                            <TableCell>{{ deposit.created_at }}</TableCell>
                            <TableCell>{{ deposit.amount_format }}</TableCell>
                            <TableCell>
                                <Link :href="route('owner.patients.show', deposit.patient_id)" class="text-sky-600">
                                    {{ deposit.patient }}
                                </Link>
                            </TableCell>
                            <TableCell>{{ deposit.service }}</TableCell>
                            <TableCell>{{ trans('' + deposit.payment_method) }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex justify-center">
                <Paginator :url="route('owner.deposits.index')" :items="deposits" />
            </div>
        </div>
    </AppLayout>
</template>
