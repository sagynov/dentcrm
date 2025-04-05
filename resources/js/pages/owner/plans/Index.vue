<script setup lang="ts">
import PlanPay from '@/components/owner/PlanPay.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { Check } from 'lucide-vue-next';

interface Props {
    plans: any;
    status?: any;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Plans'),
        href: '/owner/plans',
    },
];
</script>

<template>
    <Head :title="trans('Plans')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div v-if="status">
                <p class="text-sky-500">{{ status }}</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="flex flex-col justify-between gap-2 border" v-for="plan in plans" :key="'plan_' + plan.id">
                    <div class="flex flex-col gap-4 text-center">
                        <div class="flex min-h-[70px] flex-col items-center justify-center gap-2 bg-gray-700 p-4 text-white">
                            <h3 class="font-medium">{{ plan.name }}</h3>
                        </div>
                        <div>
                            <div class="text-3xl">{{ plan.price_format }} ₸</div>
                            <div>{{ trans('per month') }}</div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 p-4">
                        <div class="flex items-center" v-for="(feature, index) in plan.features" :key="'feature_' + index">
                            <Check :size="18" class="mr-2" /><span>{{ feature }}</span>
                        </div>
                    </div>
                    <div class="flex justify-center p-4">
                        <PlanPay :plan="plan" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
