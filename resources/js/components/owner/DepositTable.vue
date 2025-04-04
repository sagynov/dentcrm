<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

interface Props {
    deposits: any;
}

defineProps<Props>();
</script>

<template>
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
            <TableRow v-for="deposit in deposits" :key="'deposit_' + deposit.id">
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
</template>
