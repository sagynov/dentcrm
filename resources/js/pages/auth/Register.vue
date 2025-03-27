<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { LoaderCircle } from 'lucide-vue-next';
import InputMask from 'primevue/inputmask';

const form = useForm({
    name: '',
    phone: '+7',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase :title="trans('Create an account')" :description="trans('Enter your details below to create your account')">
        <Head :title="trans('Register')" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">{{trans('Name')}}</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="phone">{{trans('Phone')}}</Label>
                    <InputMask 
                        class="p-inputmask" 
                        unstyled
                        mask="+9(999)999-99-99" 
                        unmask
                        id="phone"
                        type="text"
                        required
                        :tabindex="2"
                        autocomplete="phone"
                        v-model="form.phone" 
                        placeholder="+7" 
                    />
                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">{{trans('Password')}}</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">{{trans('Confirm password')}}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{trans('Create account')}}
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                {{trans('Already have an account?')}}
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">{{trans('Log in')}}</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
