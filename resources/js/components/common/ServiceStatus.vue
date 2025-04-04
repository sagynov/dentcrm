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
            'bg-green-500 hover:bg-green-500/80': status == 'open',
            'bg-red-500 hover:bg-red-500/80': status == 'closed',
        }"
    >
        {{ trans('' + status) }}
    </Badge>
</template>
