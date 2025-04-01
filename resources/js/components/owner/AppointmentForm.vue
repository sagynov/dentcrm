<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/components/ui/toast/use-toast';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';
import DatePicker from 'primevue/datepicker';
import InputMask from 'primevue/inputmask';
import { onMounted, ref } from 'vue';
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
});

const patients = ref([]);
const doctors: any = ref([]);

const openDialog = ref(false);

const selectedPatient = ref();
const selectedDoctor = ref();

const form = useForm({
    patient_id: '',
    doctor_id: '',
    visit_date: props.date ? new Date(props.date) : new Date(),
    visit_time: props.time ?? '',
    notes: '',
    from: props.from ?? '',
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
