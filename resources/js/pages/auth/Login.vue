<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { trans } from 'laravel-vue-i18n';
import InputMask from 'primevue/inputmask';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    phone: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase :title="trans('Log in to your account')" :description="trans('Enter your phone and password below to log in')">
        <Head :title="trans('Log in')" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="phone">{{trans('Phone')}}</Label>
                    <InputMask 
                        class="p-inputmask" unstyled
                        mask="+9(999)999-99-99" 
                        unmask
                        id="phone"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="phone"
                        v-model="form.phone" 
                        placeholder="+7" 
                        value="+7"
                    />
                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">{{trans('Password')}}</Label>
                        <TextLink v-if="canResetPassword" :href="route('password.send-code')" class="text-sm" :tabindex="5">
                            {{trans('Forgot password?')}} 
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        :placeholder="trans('Password')"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between" :tabindex="3">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" v-model:checked="form.remember" :tabindex="4" />
                        <span>{{trans('Remember me')}}</span>
                    </Label>
                </div>

                <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{trans('Log in')}}
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                {{trans('Don\'t have an account?')}}
                <TextLink :href="route('register')" :tabindex="5">{{trans('Sign up')}}</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
