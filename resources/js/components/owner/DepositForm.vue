<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { useToast } from '@/components/ui/toast/use-toast';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';
import { onMounted, ref } from 'vue';
import VueMultiselect from 'vue-multiselect';

interface Props {
    patient?: any;
    service?: any;
    from?: any;
}
const props = defineProps<Props>();

onMounted(() => {
    if (props.patient) {
        patients.value.push(props.patient);
        selectedPatient.value = props.patient;
    }
    if (props.service) {
        services.value.push(props.service);
        selectedService.value = props.service;
        form.amount = props.service.price;
    }
});

const openDialog = ref(false);

const patients: any = ref([]);
const selectedPatient = ref();
const selectedService = ref();
const services: any = ref([]);
const payment_methods = [
    {
        name: 'cash',
        value: 'cash',
    },
    {
        name: 'kaspi',
        value: 'kaspi',
    },
    {
        name: 'card',
        value: 'card',
    },
];

const form = useForm({
    patient_id: '',
    service_id: '',
    amount: '',
    payment_method: '',
    from: props.from ?? '',
});

const { toast } = useToast();

const submit = () => {
    if (selectedPatient.value) {
        form.patient_id = selectedPatient.value.id;
    }
    if (selectedService.value) {
        form.service_id = selectedService.value.id;
    }
    form.post(route('owner.deposits.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: trans('Deposit added'),
                description: trans('Deposit added successfully'),
            });
            openDialog.value = false;
            form.reset();
        },
    });
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

const nameIIN = (patient: any) => {
    return patient.full_name + ' (' + patient.iin + ')';
};
const onSelect = (patient: any) => {
    axios.get(route('owner.patients.get-services', patient.id)).then(({ data }) => {
        services.value = data.services;
    });
};
const selectService = (service: any) => {
    form.amount = service.price;
};
</script>
<template>
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
                @select="onSelect"
            >
                <template #noResult>{{ trans('Enter the patient name') }}</template>
                <template #noOptions>{{ trans('Enter the patient name') }}</template>
            </VueMultiselect>
            <InputError :message="form.errors.patient_id" />
        </div>
        <div class="flex flex-col gap-4">
            <Label>{{ trans('Service') }} <span class="text-red-400">*</span></Label>
            <Select v-model="selectedService" @update:model-value="selectService">
                <SelectTrigger>
                    <SelectValue :placeholder="trans('Select')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="service in services" :key="'service_' + service.id" :value="service">
                        {{ service.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="form.errors.service_id" />
        </div>
        <div class="flex flex-col gap-4">
            <Label for="amount">{{ trans('Amount') }} <span class="text-red-400">*</span></Label>
            <Input id="amount" v-model="form.amount" class="col-span-3" autocomplete="off" />
            <InputError :message="form.errors.amount" />
        </div>
        <div class="flex flex-col gap-4">
            <Label for="payment_method">{{ trans('Payment method') }} <span class="text-red-400">*</span></Label>
            <Select id="payment_method" v-model="form.payment_method">
                <SelectTrigger>
                    <SelectValue :placeholder="trans('Select')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="(option, index) in payment_methods" :key="'paymethod_' + index" :value="option.value">
                        {{ trans('' + option.name) }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="form.errors.payment_method" />
        </div>
        <div class="flex">
            <Button @click.prevent="submit" :disbled="form.processing">{{ trans('Save') }}</Button>
        </div>
    </div>
</template>
