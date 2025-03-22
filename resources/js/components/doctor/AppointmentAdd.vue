<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useToast } from '@/components/ui/toast/use-toast';
import { useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import InputMask from 'primevue/inputmask';
import Dialog from 'primevue/dialog';
import DatePicker from 'primevue/datepicker';
import { ref } from 'vue'
import Select from 'primevue/select';
import { Textarea } from '@/components/ui/textarea';
import VueMultiselect from 'vue-multiselect'
import axios from 'axios';

interface Props {
    patients: any;
}

const props = defineProps<Props>();

const patients = ref(props.patients);

const openDialog = ref(false);

const selectedPatient = ref();

const form = useForm({
    patient_id: '',
    doctor_id: '',
    visit_date: new Date(),
    visit_time: '',
    notes: '',
});

const { toast } = useToast();

const submit = () => {
    form.patient_id = selectedPatient.value.id;
    form.post(route('doctor.appointments.store'), {
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

const asyncFind = (event: any) => {
    console.log(event);
    if(event.length > 1){
        axios.get(route('doctor.appointments.search-patient'), {
            params: {query: event}
        }).then(({data}) => {
            patients.value = data.patients;
            console.log(data);
        }).catch((err) => {
            console.log(err);
        });
    }
}

const nameIIN = (patient: any) => {
    return patient.full_name+' ('+patient.iin+')';
}
</script>

<template>
    <Button @click="openDialog = true" class="w-full">{{ trans('Add appointment') }}</Button>
    <Dialog v-model:visible="openDialog" modal :header="trans('Add appointment')">
        <div class="flex flex-col gap-6 overflow-y-auto px-2 py-4">
            <div class="flex flex-col gap-4">
                <Label for="patient_id">{{ trans('Patient') }}</Label>
                <VueMultiselect :placeholder="trans('Select')" v-model="selectedPatient" :options="patients" @search-change="asyncFind" :custom-label="nameIIN">
                    <template #noResult>{{ trans('Enter the patient name') }}</template>
                    <template #noOptions>{{ trans('Enter the patient name') }}</template>
                </VueMultiselect>
                <InputError :message="form.errors.patient_id" />
            </div>
            <div class="flex flex-col gap-4">
                <Label for="visit_date">{{ trans('Visit date') }}</Label>
                <div>
                    <DatePicker v-model="form.visit_date" id="visit_date" inline />
                </div>
                <InputError :message="form.errors.visit_date" />
            </div>
            <div class="flex flex-col gap-4">
                <Label for="visit_time">{{ trans('Visit time') }}</Label>
                <InputMask class="p-inputmask" unstyled mask="99:99" id="visit_time" v-model="form.visit_time" placeholder="__:__" />
                <InputError :message="form.errors.visit_time" />
            </div>
            
            <div class="flex flex-col gap-4">
                <Label for="notes">{{ trans('Notes') }}</Label>
                <Textarea id="notes" v-model="form.notes" class="col-span-3" />
                <InputError :message="form.errors.notes" />
            </div>
            <div class="flex justify-end">
                <div class="flex gap-2">
                    <Button type="button" @click="openDialog = false" variant="secondary">
                        {{ trans('Close') }}
                    </Button>
                    <Button @click.prevent="submit" :disbled="form.processing">{{ trans('Save') }}</Button>
                </div>
            </div>
        </div>
    </Dialog>
</template>
