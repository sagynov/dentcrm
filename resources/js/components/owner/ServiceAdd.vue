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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useToast } from '@/components/ui/toast/use-toast';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';
import { ref } from 'vue';
import VueMultiselect from 'vue-multiselect';
import Textarea from '../ui/textarea/Textarea.vue';

interface Props {
    clinic_services: any;
}

defineProps<Props>();

const openDialog = ref(false);

const patients: any = ref([]);
const doctors: any = ref([]);
const selectedPatient = ref();
const selectedDoctor = ref();

const form = useForm({
    patient_id: '',
    doctor_id: '',
    clinic_service_id: '',
    name: '',
    price: '',
    description: '',
});

const { toast } = useToast();

const submit = () => {
    if (selectedPatient.value) {
        form.patient_id = selectedPatient.value.id;
    }
    if (selectedDoctor.value) {
        form.doctor_id = selectedDoctor.value.id;
    }
    form.post(route('owner.services.store'), {
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

const selectClinicService = (event: any) => {
    form.clinic_service_id = event.id;
    form.name = event.name;
    form.price = event.base_price;
    form.description = event.description;
};

const searchPatient = (event: any) => {
    if (event.length > 1) {
        axios
            .get(route('search-patient'), {
                params: { query: event },
            })
            .then(({ data }) => {
                patients.value = data.patients;
            })
            .catch((err) => {
                console.log(err);
            });
    }
};
const searchDoctor = (event: any) => {
    if (event.length > 1) {
        axios
            .get(route('search-doctor'), {
                params: { query: event },
            })
            .then(({ data }) => {
                doctors.value = data.doctors;
            })
            .catch((err) => {
                console.log(err);
            });
    }
};

const nameIIN = (patient: any) => {
    return patient.full_name + ' (' + patient.iin + ')';
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
                    <Label for="patient_id">{{ trans('Patient') }} <span class="text-red-400">*</span></Label>
                    <VueMultiselect
                        id="patient_id"
                        :placeholder="trans('Select')"
                        v-model="selectedPatient"
                        :options="patients"
                        @search-change="searchPatient"
                        :custom-label="nameIIN"
                    >
                        <template #noResult>{{ trans('Enter the patient name') }}</template>
                        <template #noOptions>{{ trans('Enter the patient name') }}</template>
                    </VueMultiselect>
                    <InputError :message="form.errors.patient_id" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="doctor_id">{{ trans('Doctor') }} <span class="text-red-400">*</span></Label>
                    <VueMultiselect
                        id="doctor_id"
                        :placeholder="trans('Select')"
                        v-model="selectedDoctor"
                        :options="doctors"
                        @search-change="searchDoctor"
                        label="full_name"
                    >
                        <template #noResult>{{ trans('Enter the doctor name') }}</template>
                        <template #noOptions>{{ trans('Enter the doctor name') }}</template>
                    </VueMultiselect>
                    <InputError :message="form.errors.doctor_id" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label>{{ trans('Service type') }} <span class="text-red-400">*</span></Label>
                    <Select @update:model-value="selectClinicService">
                        <SelectTrigger>
                            <SelectValue :placeholder="trans('Select')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="clinic_service in clinic_services" :key="'option_' + clinic_service.id" :value="clinic_service">
                                {{ clinic_service.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.clinic_service_id" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="name">{{ trans('Service name') }} <span class="text-red-400">*</span></Label>
                    <Input id="name" v-model="form.name" class="col-span-3" autocomplete="off" />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="flex flex-col gap-4">
                    <Label for="price">{{ trans('Price') }} <span class="text-red-400">*</span></Label>
                    <Input id="price" v-model="form.price" class="col-span-3" autocomplete="off" />
                    <InputError :message="form.errors.price" />
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
