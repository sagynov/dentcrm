<script setup lang="ts">
import Paginator from '@/components/common/Paginator.vue';
import DepositTable from '@/components/owner/DepositTable.vue';
import { Button } from '@/components/ui/button';
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
                <DepositTable :deposits="deposits.data" />
            </div>
            <div class="flex justify-center">
                <Paginator :url="route('owner.deposits.index')" :items="deposits" />
            </div>
        </div>
    </AppLayout>
</template>
