<script setup lang="ts">
import axios from 'axios';
import { trans } from 'laravel-vue-i18n';
import { ref } from 'vue';
import VueMultiselect from 'vue-multiselect';

const emit = defineEmits(['onSelect']);

const patients: any = ref([]);

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

const onSelect = (patient: any) => {
    emit('onSelect', patient);
};

const nameIIN = (patient: any) => {
    return patient.full_name + ' (' + patient.iin + ')';
};
</script>

<template>
    <VueMultiselect :placeholder="trans('Select')" :options="patients" @search-change="searchPatient" :custom-label="nameIIN" @select="onSelect">
        <template #noResult>{{ trans('Enter the patient name') }}</template>
        <template #noOptions>{{ trans('Enter the patient name') }}</template>
    </VueMultiselect>
</template>
