<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useToast } from '@/components/ui/toast/use-toast';
import { useForm, usePage  } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import Dialog from 'primevue/dialog';
import { ref } from 'vue'
import { Textarea } from '@/components/ui/textarea';
import axios from 'axios';
import FileUpload from 'primevue/fileupload';
import Badge from 'primevue/badge';
import Message from 'primevue/message';
const page = usePage()

interface Props {
    patient: any;
}
const props = defineProps<Props>();
const openDialog = ref(false);
const form = useForm({
    comment: '',
    attachments: '[]',
});
const { toast } = useToast();
const submit = () => {
    form.post(route('doctor.patients.add-record', props.patient.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: trans('Record added'),
                description: trans('Record added successfully'),
            });
            openDialog.value = false;
            form.reset(); 
        },
    });
};
const beforeSend = (request: any) => {
    request.formData.append('_token', page.props.csrf_token);
    return request;
}
const onUpload = (event: any) => {
    let attachments = JSON.parse(form.attachments);
    let response = JSON.parse(event.xhr.response);
    attachments = attachments.concat(response.files);
    form.attachments = JSON.stringify(attachments);
}
</script>

<template>
    <Button @click="openDialog = true" class="w-full">{{ trans('Add patient record') }}</Button>
    <Dialog v-model:visible="openDialog" modal :header="trans('Add patient record')" class="w-[350px] sm:w-[450px]">
        <div class="flex flex-col gap-4 overflow-y-auto px-2 py-4">
            <div class="flex flex-col gap-4">
                <Label for="comment">{{ trans('Comment') }}</Label>
                <Textarea id="comment" v-model="form.comment" class="col-span-3" />
                <InputError :message="form.errors.comment" />
            </div>
            <div class="flex flex-col gap-4">
                <Label for="attachments">{{trans('Attachments')}}</Label>
                <FileUpload name="attachments[]" :show-cancel-button="false" :show-upload-button="false" :choose-label="trans('Choose')" :upload-label="trans('Upload')" :cancel-label="trans('Cancel')" :url="route('doctor.patients.upload-record-attachment', patient.id)" @before-send="beforeSend" @upload="onUpload" :multiple="true" :auto="true" accept="application/pdf,image/jpeg,image/png"  :maxFileSize="1000000">
                    <template #empty>
                        <span>{{ trans('Drag and drop files to here to upload') }}</span>
                    </template>
                    <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback, messages }">
                        <div class="flex flex-col gap-4">
                            <Message v-for="message of messages" :key="message" :class="{ 'mb-2': !files.length && !uploadedFiles.length}" severity="error">
                                {{ message }}
                            </Message>
                            <div v-if="uploadedFiles.length > 0">
                                <div v-for="(file, index) of uploadedFiles" :key="file.name + file.type + file.size" class="p-2 border-b overflow-hidden max-w-full">
                                    <span class="font-semibold text-ellipsis max-w-60 whitespace-nowrap overflow-hidden">{{ file.name }}</span>
                                </div>
                            </div>
                        </div>
                    </template>
                </FileUpload>
                <InputError :message="form.errors.attachments" />
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
