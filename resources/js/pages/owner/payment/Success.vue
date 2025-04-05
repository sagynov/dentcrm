<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Successful payment'),
        href: '/owner/payments/success',
    },
];
</script>

<template>
    <Head :title="trans('Successful payment')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col items-center justify-center gap-4 rounded-xl p-4">
            <div class="max-w-md rounded-2xl bg-white p-8 text-center shadow-lg">
                <div class="mb-4 flex items-center justify-center">
                    <svg class="h-16 w-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4 -4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="mb-2 text-2xl font-bold text-gray-800">Оплата прошла успешно!</h2>
                <p class="mb-6 text-gray-600">Благодарим за оплату. Ваш тариф был успешно активирован.</p>
                <Link href="/owner" class="inline-block rounded-lg bg-blue-600 px-6 py-2 text-white transition hover:bg-blue-700">
                    Перейти в личный кабинет
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
