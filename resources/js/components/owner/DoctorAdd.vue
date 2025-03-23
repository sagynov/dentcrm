<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useToast } from '@/components/ui/toast/use-toast';
import { type SharedData } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import InputMask from 'primevue/inputmask';
import { ref } from 'vue';

import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';


const openDialog = ref(false);

const form = useForm({
    first_name: '',
    last_name: '',
    phone: '',
    speciality: '',
});
const options = [
    {
        name: trans('General Dentist (Therapist)'),
        value: 'General Dentist (Therapist)',
    },
    {
        name: trans('Prosthodontist (Orthopedic Dentist)'),
        value: 'Prosthodontist (Orthopedic Dentist)',
    },
    {
        name: trans('Oral Surgeon'),
        value: 'Oral Surgeon',
    },
    {
        name: trans('Orthodontist'),
        value: 'Orthodontist',
    },
];

const { toast } = useToast();

const submit = () => {
    form.post(route('owner.doctors.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: trans('Doctor added'),
                description: trans('Doctor added successfully'),
            });
            openDialog.value = false;
            form.reset();
        },
    });
};
</script>

<template>
    <Dialog v-model:open="openDialog">
        <DialogTrigger as-child>
            <Button @click="openDialog = true" class="w-full">{{ trans('Add doctor') }}</Button>
        </DialogTrigger>
        <DialogContent class="max-h-[500px] grid-rows-[auto_minmax(0,1fr)_auto]">
            <DialogHeader>
                <DialogTitle>{{ trans('Add doctor') }}</DialogTitle>
                <DialogDescription>
                    <p>{{ trans('Add doctor') }}</p>
                </DialogDescription>
            </DialogHeader>
            <div class="flex flex-col gap-6 overflow-y-auto px-2 py-4">
                <div class="flex flex-col gap-4">
                    <Label for="first_name">{{ trans('First name') }}</Label>
                    <Input id="first_name" v-model="form.first_name" class="col-span-3" autocomplete="off" />
                    <InputError :message="form.errors.first_name" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="last_name">{{ trans('Last name') }}</Label>
                    <Input id="last_name" v-model="form.last_name" class="col-span-3" />
                    <InputError :message="form.errors.last_name" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="phone">{{ trans('Phone') }}</Label>
                    <InputMask class="p-inputmask" unstyled mask="+9(999)999-99-99" unmask id="phone" v-model="form.phone" placeholder="+7" autocomplete="off" />
                    <InputError :message="form.errors.phone" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="phone">{{ trans('Speciality') }}</Label>
                    <Select v-model="form.speciality">
                        <SelectTrigger>
                            <SelectValue :placeholder="trans('Select')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectItem v-for="(option, index) in options" :key="'option_'+index" :value="option.value">
                                    {{ option.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.speciality" />
                </div>
            </div>
            <DialogFooter class="gap-2">
                <DialogClose as-child>
                    <Button type="button" variant="secondary">
                        {{ trans('Close') }}
                    </Button>
                </DialogClose>
                <Button @click.prevent="submit" :disbled="form.processing">{{ trans('Save') }}</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
