<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
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


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Dashboard'),
        href: '/doctor',
    },
];
</script>

<template>
    <Head :title="trans('Dashboard')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-4 items-center justify-between">
                <div>
                    <h2 class="font-medium text-gray-700">{{ trans('Dashboard') }}</h2>
                </div>
                <div class="flex justify-end">
                    
                </div>
            </div>
            <div class="overflow-x-auto max-w-full">
                
            </div>
        </div>
    </AppLayout>
</template>