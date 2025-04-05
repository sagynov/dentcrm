<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Payment failed'),
        href: '/owner/payments/fail',
    },
];
</script>

<template>
    <Head :title="trans('Payment failed')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col items-center justify-center gap-4 rounded-xl p-4">
            <div class="max-w-md rounded-2xl bg-white p-8 text-center shadow-lg">
                <div class="mb-4 flex items-center justify-center">
                    <svg class="h-16 w-16 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h2 class="mb-2 text-2xl font-bold text-gray-800">Оплата не прошла</h2>
                <p class="mb-6 text-gray-600">К сожалению, произошла ошибка при обработке платежа. Пожалуйста, попробуйте ещё раз.</p>
                <Link href="/owner/plans" class="inline-block rounded-lg bg-red-600 px-6 py-2 text-white transition hover:bg-red-700">
                    Повторить оплату
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
