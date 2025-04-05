<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { EllipsisVertical } from 'lucide-vue-next';
import { useConfirm } from 'primevue/useconfirm';

interface Props {
    service: any;
}

const props = defineProps<Props>();

const confirm = useConfirm();

const close = () => {
    confirm.require({
        message: trans('Do you want to close the service?'),
        header: trans('Confirmation'),
        rejectProps: {
            label: trans('Cancel'),
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: trans('Yes'),
        },
        accept: () => {
            router.visit(route('doctor.services.close', props.service.id));
        },
    });
};
</script>
<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost">
                <EllipsisVertical :size="16" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
            <DropdownMenuLabel>{{ trans('Action') }}</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuGroup>
                <DropdownMenuItem v-if="service.status == 'open'">
                    <a class="block w-full cursor-pointer" @click.prevent="close">
                        {{ trans('Close') }}
                    </a>
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
