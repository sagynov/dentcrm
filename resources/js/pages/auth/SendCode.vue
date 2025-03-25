<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { LoaderCircle } from 'lucide-vue-next';
import InputMask from 'primevue/inputmask';

defineProps<{
    status?: string;
}>();

const form = useForm({
    phone: '',
});

const submit = () => {
    form.post(route('password.generate-code'));
};
</script>

<template>
    <AuthLayout :title="trans('Reset password')" :description="trans('Enter your phone to receive a code')">
        <Head :title="trans('Reset password')" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-red-600">
            {{ status }}
        </div>

        <div class="space-y-6">
            <form @submit.prevent="submit">
                <div class="grid gap-2">
                    <Label for="phone">{{ trans('Phone') }}</Label>
                    <InputMask
                        class="p-inputmask"
                        unstyled
                        mask="+9(999)999-99-99"
                        unmask
                        id="phone"
                        type="text"
                        autocomplete="off"
                        v-model="form.phone"
                        autofocus
                        placeholder="+7"
                    />
                    <InputError :message="form.errors.phone" />
                </div>

                <div class="my-6 flex items-center justify-start">
                    <Button class="w-full" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        {{ trans('Send code') }}
                    </Button>
                </div>
            </form>

            <div class="space-x-1 text-center text-sm text-muted-foreground">
                <span>{{ trans('Or, return to') }}</span>
                <TextLink :href="route('login')">{{ trans('log in') }}</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
