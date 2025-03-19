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
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';

const selectedCountry = ref();
const countries = ref([
    { name: 'Patient 1', code: 'AU' },
    { name: 'Patient 2', code: 'BR' },
]);

const page = usePage<SharedData>();
const clinic = page.props.clinic as string;

const openDialog = ref(false);

const form = useForm({
    name: '',
    visit_date: new Date(),
    visit_time: '',
    specialization: '',
    address: '',
    phone: '',
    email: '',
    website: '',
});
const options = [
    {
        name: trans('Dental Clinic'),
        value: 'Dental Clinic',
    },
];

const { toast } = useToast();

const submit = () => {
    form.post(route('owner.clinics.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: trans('Clinic added'),
                description: trans('Clinic added successfully'),
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
            <Button @click="openDialog = true" class="w-full">{{ trans('Add clinic') }}</Button>
        </DialogTrigger>
        <DialogContent class="max-h-[500px] grid-rows-[auto_minmax(0,1fr)_auto]">
            <DialogHeader>
                <DialogTitle>{{ trans('Add clinic') }}</DialogTitle>
                <DialogDescription>
                    <p>{{ trans('Add clinic') }}</p>
                </DialogDescription>
            </DialogHeader>
            <div class="flex flex-col gap-6 overflow-y-auto px-2 py-4">
                <div class="flex flex-col gap-4">
                    <Label for="name">{{ trans('Patient') }}</Label>
                    <Select v-model="selectedCountry" :options="countries" filter optionLabel="name" placeholder="Select a Country" class="w-full md:w-56">
                        <template #value="slotProps">
                            <div v-if="slotProps.value" class="flex items-center">
                                <img :alt="slotProps.value.label" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png" :class="`mr-2 flag flag-${slotProps.value.code.toLowerCase()}`" style="width: 18px" />
                                <div>{{ slotProps.value.name }}</div>
                            </div>
                            <span v-else>
                                {{ slotProps.placeholder }}
                            </span>
                        </template>
                        <template #option="slotProps">
                            <div class="flex items-center">
                                <img :alt="slotProps.option.label" src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png" :class="`mr-2 flag flag-${slotProps.option.code.toLowerCase()}`" style="width: 18px" />
                                <div>{{ slotProps.option.name }}</div>
                            </div>
                        </template>
                    </Select>
                    <InputError :message="form.errors.name" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="visit_date">{{ trans('Visit date') }}</Label>
                    <div>
                        <DatePicker id="visit_date" v-model="form.visit_date" inline />
                    </div>
                    <InputError :message="form.errors.visit_date" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="visit_time">{{ trans('Visit time') }}</Label>
                    <InputMask class="p-inputmask" unstyled mask="99:99" id="visit_time" v-model="form.visit_time" placeholder="__:__" />
                    <InputError :message="form.errors.visit_time" />
                </div>
                
                <div class="flex flex-col gap-4">
                    <Label for="address">{{ trans('Address') }}</Label>
                    <Input id="address" v-model="form.address" class="col-span-3" />
                    <InputError :message="form.errors.address" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="phone">{{ trans('Phone number') }}</Label>
                    <InputMask class="p-inputmask" unstyled mask="+9(999)999-99-99" unmask id="phone" v-model="form.phone" placeholder="+7" />
                    <InputError :message="form.errors.phone" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="email">{{ trans('Email') }}</Label>
                    <Input id="email" v-model="form.email" class="col-span-3" />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="website">{{ trans('Website') }}</Label>
                    <Input id="website" v-model="form.website" class="col-span-3" />
                    <InputError :message="form.errors.website" />
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
