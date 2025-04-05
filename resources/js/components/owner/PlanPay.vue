<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { halyk } from '@/payment-api';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';
import { ref } from 'vue';

interface Props {
    plan: any;
}

const props = defineProps<Props>();

const openDialog = ref(false);

const payMethod = (bank: string) => {
    if (bank == 'halyk') {
        axios
            .post(route('owner.payments.store'), {
                paymethod: 'halyk',
                plan: props.plan.slug,
            })
            .then(({ data }) => {
                console.log(data);
                halyk.pay(data);
            });
    }
};
</script>

<template>
    <Dialog v-model:open="openDialog">
        <DialogTrigger as-child>
            <Button @click="openDialog = true" class="w-full">{{ trans('Choose') }}</Button>
        </DialogTrigger>
        <DialogContent class="max-h-[500px] grid-rows-[auto_minmax(0,1fr)_auto]">
            <DialogHeader>
                <DialogTitle>{{ plan.name }}</DialogTitle>
                <DialogDescription>
                    <p>{{ trans('Pay method') }}</p>
                </DialogDescription>
            </DialogHeader>
            <div class="flex flex-col gap-6 overflow-y-auto px-2 py-4">
                <div class="mb-4 rounded-md border bg-gray-50 p-4">
                    <p class="text-lg font-medium">
                        Тариф: <span class="text-blue-600">{{ plan.name }}</span>
                    </p>
                    <p class="text-lg font-medium">
                        Цена: <span class="text-green-600">{{ plan.price_format }}</span> / месяц
                    </p>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <button
                    @click="payMethod('halyk')"
                    class="mb-2 flex w-full items-center justify-center rounded-md bg-green-500 px-4 py-2 text-white hover:bg-green-600"
                >
                    Оплатить через Halyk Bank
                </button>
                <button
                    v-if="false"
                    @click="payMethod('bcc')"
                    class="flex w-full items-center justify-center rounded-md bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600"
                >
                    Оплатить через BCC Bank
                </button>
            </div>
        </DialogContent>
    </Dialog>
</template>
