<script setup lang="ts">
import Toaster from '@/components/ui/toast/Toaster.vue';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <Toaster />
    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
</template>
