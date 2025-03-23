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
import { useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import InputMask from 'primevue/inputmask';
import { ref } from 'vue';
import axios from 'axios';

const openDialog = ref(false);

const form = useForm({
    iin: '',
    first_name: '',
    last_name: '',
    phone: '',
    birth_date: '',
});

const { toast } = useToast();

const submit = () => {
    form.post(route('owner.patients.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: trans('Patient added'),
                description: trans('Patient added successfully'),
            });
            openDialog.value = false;
            form.reset();
        },
    });
};

const checkIIN = (value: any) => {
    if(value.length == 12){
        axios.get(route('find-by-iin', {iin: value})).then(({data}: any) => {
            form.first_name = data.first_name;
            form.last_name = data.last_name;
        }).catch(err => {
            console.log(err);
        });
    }

}
</script>

<template>
    <Dialog v-model:open="openDialog">
        <DialogTrigger as-child>
            <Button @click="openDialog = true" class="w-full">{{ trans('Add patient') }}</Button>
        </DialogTrigger>
        <DialogContent class="max-h-[500px] grid-rows-[auto_minmax(0,1fr)_auto]">
            <DialogHeader>
                <DialogTitle>{{ trans('Add patient') }}</DialogTitle>
                <DialogDescription>
                    <p>{{ trans('Add patient') }}</p>
                </DialogDescription>
            </DialogHeader>
            <div class="flex flex-col gap-6 overflow-y-auto px-2 py-4">
                <div class="flex flex-col gap-4">
                    <Label for="iin">{{ trans('IIN') }}</Label>
                    <InputMask  @update:model-value="checkIIN" unmask class="p-inputmask" unstyled mask="999999999999" id="iin" v-model="form.iin" placeholder="____________" autocomplete="off" />
                    <InputError :message="form.errors.iin" />
                </div>
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
                    <Label for="birth_date">{{ trans('Birth date') }}</Label>
                    <InputMask class="p-inputmask" unstyled mask="99-99-9999" id="birth_date" v-model="form.birth_date" placeholder="__-__-____" autocomplete="off" />
                    <InputError :message="form.errors.birth_date" />
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
