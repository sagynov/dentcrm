<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { trans } from 'laravel-vue-i18n';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Doctors'),
        href: '/doctors',
    },
];
</script>

<template>
    <Head :title="trans('Doctors')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

        </div>
    </AppLayout>
</template>