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
    appointment: any;
}

const props = defineProps<Props>();

const confirm = useConfirm();
const cancel = () => {
    confirm.require({
        message: trans('Do you want to cancel your appointment?'),
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
            router.visit(route('owner.appointments.cancel', props.appointment.id));
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
                <DropdownMenuItem v-if="appointment.status == 'scheduled'">
                    <a class="block w-full cursor-pointer" @click.prevent="cancel">
                        {{ trans('Cancel') }}
                    </a>
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
