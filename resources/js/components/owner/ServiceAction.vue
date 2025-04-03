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
import { Link } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { EllipsisVertical } from 'lucide-vue-next';

interface Props {
    service: any;
}

defineProps<Props>();
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
                <DropdownMenuItem>
                    <Link :href="route('owner.deposits.create') + '?patient=' + service.patient_id + '&service=' + service.id + '&from=services'">{{
                        trans('Add deposit')
                    }}</Link>
                </DropdownMenuItem>
                <DropdownMenuItem>
                    <span>{{ trans('Close') }}</span>
                </DropdownMenuItem>
            </DropdownMenuGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
