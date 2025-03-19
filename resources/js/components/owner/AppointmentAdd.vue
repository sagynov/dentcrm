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
import { Patient } from '@/types';

interface Props {
    patients: Array<Patient>;
    doctors: any;
}

defineProps<Props>();

const openDialog = ref(false);

const form = useForm({
    patient_id: '',
    doctor_id: '',
    visit_date: new Date(),
    visit_time: '',
    notes: '',
});

const { toast } = useToast();

const submit = () => {
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
const dateSelected = (event: any) => {
    form.visit_date = event.toLocaleDateString();
}
</script>

<template>
    <Button @click="openDialog = true" class="w-full">{{ trans('Add appointment') }}</Button>
    <Dialog v-model:visible="openDialog" modal :header="trans('Add appointment')">
        <div class="flex flex-col gap-6 overflow-y-auto px-2 py-4">
            <div class="flex flex-col gap-4">
                <Label for="patient_id">{{ trans('Patient') }}</Label>
                <Select v-model="form.patient_id" :options="patients" filter option-value="id" optionLabel="full_name" placeholder="Select a patient">
                </Select>
                <InputError :message="form.errors.patient_id" />
            </div>
            <div class="flex flex-col gap-4">
                <Label for="doctor_id">{{ trans('Doctor') }}</Label>
                <Select v-model="form.doctor_id" :options="doctors" filter option-value="id" optionLabel="full_name" placeholder="Select a patient">
                </Select>
                <InputError :message="form.errors.doctor_id" />
            </div>
            <div class="flex flex-col gap-4">
                <Label for="visit_date">{{ trans('Visit date') }}</Label>
                <div>
                    <DatePicker id="visit_date" date-format="d-m-Y" @value-change="dateSelected" inline />
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
                    <Button type="button" variant="secondary">
                        {{ trans('Close') }}
                    </Button>
                    <Button @click.prevent="submit" :disbled="form.processing">{{ trans('Save') }}</Button>
                </div>
            </div>
        </div>
    </Dialog>
</template>
