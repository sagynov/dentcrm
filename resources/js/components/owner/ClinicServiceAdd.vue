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
import { ref } from 'vue';
import Textarea from '../ui/textarea/Textarea.vue';

interface Props {
    clinic: any;
}

const props = defineProps<Props>();

const openDialog = ref(false);

const form = useForm({
    name: '',
    base_price: '',
    description: '',
});

const { toast } = useToast();

const submit = () => {
    form.post(route('owner.clinics.addService', props.clinic.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: trans('Service added'),
                description: trans('Service added successfully'),
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
            <Button @click="openDialog = true" class="w-full">{{ trans('Add service') }}</Button>
        </DialogTrigger>
        <DialogContent class="max-h-[500px] grid-rows-[auto_minmax(0,1fr)_auto]">
            <DialogHeader>
                <DialogTitle>{{ trans('Add service') }}</DialogTitle>
                <DialogDescription>
                    <p>{{ trans('Add service') }}</p>
                </DialogDescription>
            </DialogHeader>
            <div class="flex flex-col gap-6 overflow-y-auto px-2 py-4">
                <div class="flex flex-col gap-4">
                    <Label for="name">{{ trans('Service name') }} <span class="text-red-400">*</span></Label>
                    <Input id="name" v-model="form.name" class="col-span-3" autocomplete="off" />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="base_price">{{ trans('Base price') }} <span class="text-red-400">*</span></Label>
                    <Input id="base_price" v-model="form.base_price" class="col-span-3" autocomplete="off" />
                    <InputError :message="form.errors.base_price" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="description">{{ trans('Description') }}</Label>
                    <Textarea id="description" v-model="form.description" class="col-span-3" autocomplete="off" />
                    <InputError :message="form.errors.description" />
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
