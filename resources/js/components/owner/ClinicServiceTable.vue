<script setup lang="ts">
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { trans } from 'laravel-vue-i18n';

interface Props {
    services: any;
}
defineProps<Props>();
</script>

<template>
    <Table>
        <TableCaption>{{ trans('A list of services') }}</TableCaption>
        <TableHeader>
            <TableRow>
                <TableHead>{{ trans('Service name') }}</TableHead>
                <TableHead>{{ trans('Base price') }}</TableHead>
                <TableHead>{{ trans('Description') }}</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="service in services" :key="'service_' + service.id">
                <TableCell>{{ service.name }}</TableCell>
                <TableCell>{{ service.base_price }}</TableCell>
                <TableCell>{{ service.description }}</TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
