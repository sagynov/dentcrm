<script setup lang="ts">
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

import { PinInput, PinInputGroup, PinInputInput } from '@/components/ui/pin-input';
import { trans } from 'laravel-vue-i18n';
import { ref } from 'vue';

const props = defineProps<{
    status?: string;
    phone: string;
}>();

const form = useForm({
    phone: props.phone,
    code: '',
});

const value = ref<string[]>(['', '', '', '']);

const handleComplete = (e: string[]) => {
    form.code = e.join('');
    form.post(route('password.check-code'));
};
</script>

<template>
    <AuthLayout :title="trans('Enter code')" :description="trans('Enter your code')">
        <Head title="Enter Code" />
        <div v-if="status" class="mb-4 text-center text-sm font-medium text-red-600">
            {{ status }}
        </div>
        <div class="flex justify-center space-y-6">
            <PinInput id="code" v-model="value" placeholder="○" @complete="handleComplete">
                <PinInputGroup class="gap-1">
                    <template v-for="(id, index) in 4" :key="id">
                        <PinInputInput class="rounded-md border" :index="index" />
                    </template>
                </PinInputGroup>
            </PinInput>
        </div>
    </AuthLayout>
</template>
