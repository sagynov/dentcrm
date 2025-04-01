<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { trans } from 'laravel-vue-i18n';

interface Props {
    status: any;
}

defineProps<Props>();
</script>
<template>
    <Badge
        :class="{
            'bg-sky-500 hover:bg-sky-500/80': status == 'scheduled',
            'bg-green-500 hover:bg-green-500/80': status == 'completed',
            'bg-red-500 hover:bg-red-500/80': status == 'canceled',
        }"
    >
        {{ trans('' + status) }}
    </Badge>
</template>
