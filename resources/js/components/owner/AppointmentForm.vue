<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/components/ui/toast/use-toast';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';
import DatePicker from 'primevue/datepicker';
import InputMask from 'primevue/inputmask';
import { computed, onMounted, ref } from 'vue';
import VueMultiselect from 'vue-multiselect';

interface Props {
    date?: any;
    time?: any;
    doctor?: any;
    from?: any;
}

const props = defineProps<Props>();

onMounted(() => {
    if (props.doctor) {
        doctors.value.push(props.doctor);
        selectedDoctor.value = props.doctor;
    }
    axios.get(route('owner.clinics.get-services')).then(({ data }) => {
        clinic_services.value = data.services;
    });
});

const patients: any = ref([]);
const doctors: any = ref([]);
const clinic_services: any = ref([]);
const services: any = ref([]);

const openDialog = ref(false);

const selectedPatient = ref();
const selectedDoctor = ref();
const showServiceType = computed(() => {
    if (form.service_id == '0') {
        return true;
    }
    return false;
});

const form = useForm({
    patient_id: '',
    doctor_id: '',
    service_id: '',
    visit_date: props.date ? new Date(props.date) : new Date(),
    visit_time: props.time ?? '',
    notes: '',
    from: props.from ?? '',
    clinic_service_id: '',
    service_name: '',
    service_price: '',
    service_description: '',
});

const { toast } = useToast();

const submit = () => {
    if (selectedPatient.value) {
        form.patient_id = selectedPatient.value.id;
    }
    if (selectedDoctor.value) {
        form.doctor_id = selectedDoctor.value.id;
    }
    form.post(route('owner.appointments.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: trans('Appointment added'),
                description: trans('Appointment added successfully'),
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

const onSelect = (patient: any) => {
    axios.get(route('owner.patients.get-services', patient.id)).then(({ data }) => {
        services.value = data.services;
    });
};

const selectClinicService = (event: any) => {
    form.clinic_service_id = event.id;
    form.service_name = event.name;
    form.service_price = event.base_price;
    form.service_description = event.description;
};
const selectService = (service: any) => {
    if (service !== '0') {
        doctors.value.push(service.doctor);
        selectedDoctor.value = service.doctor;
        form.service_id = service.id;
    } else {
        form.service_id = '0';
    }
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
            <Select @update:model-value="selectService">
                <SelectTrigger>
                    <SelectValue :placeholder="trans('Select')" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="service in services" :key="'service_' + service.id" :value="service">
                        {{ service.name }}
                    </SelectItem>
                    <SelectItem value="0">
                        {{ trans('New Service') }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="form.errors.service_id" />
        </div>
        <template v-if="showServiceType">
            <div class="flex flex-col gap-4">
                <Label>{{ trans('Service type') }} <span class="text-red-400">*</span></Label>
                <Select @update:model-value="selectClinicService">
                    <SelectTrigger>
                        <SelectValue :placeholder="trans('Select')" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="clinic_service in clinic_services" :key="'clinic_service_' + clinic_service.id" :value="clinic_service">
                            {{ clinic_service.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.clinic_service_id" />
            </div>
            <div class="flex flex-col gap-4">
                <Label for="service_name">{{ trans('Service name') }} <span class="text-red-400">*</span></Label>
                <Input id="service_name" v-model="form.service_name" class="col-span-3" autocomplete="off" />
                <InputError :message="form.errors.service_name" />
            </div>
            <div class="flex flex-col gap-4">
                <Label for="service_price">{{ trans('Price') }} <span class="text-red-400">*</span></Label>
                <Input id="service_price" v-model="form.service_price" class="col-span-3" autocomplete="off" />
                <InputError :message="form.errors.service_price" />
            </div>
            <div class="flex flex-col gap-4">
                <Label for="service_description">{{ trans('Description') }}</Label>
                <Textarea id="service_description" v-model="form.service_description" class="col-span-3" autocomplete="off" />
                <InputError :message="form.errors.service_description" />
            </div>
        </template>
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
            <Label>{{ trans('Visit date') }} <span class="text-red-400">*</span></Label>
            <div>
                <DatePicker v-model="form.visit_date" id="visit_date" inline />
            </div>
            <InputError :message="form.errors.visit_date" />
        </div>
        <div class="flex flex-col gap-4">
            <Label for="visit_time">{{ trans('Visit time') }} <span class="text-red-400">*</span></Label>
            <InputMask class="p-inputmask" unstyled mask="99:99" id="visit_time" v-model="form.visit_time" placeholder="__:__" />
            <InputError :message="form.errors.visit_time" />
        </div>

        <div class="flex flex-col gap-4">
            <Label for="notes">{{ trans('Notes') }}</Label>
            <Textarea id="notes" v-model="form.notes" class="col-span-3" />
            <InputError :message="form.errors.notes" />
        </div>
        <div class="flex">
            <Button @click.prevent="submit" :disbled="form.processing">{{ trans('Save') }}</Button>
        </div>
    </div>
</template>
