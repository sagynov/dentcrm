<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from '@/components/ui/pagination';
import { router } from '@inertiajs/vue3';

interface Props {
    url: any;
    items: any;
}
const props = defineProps<Props>();
const setPage = (page: number) => {
    router.get(props.url, { page }, { preserveState: true, preserveScroll: true });
};
</script>
<template>
    <Pagination
        v-slot="{ page }"
        @update:page="setPage"
        :items-per-page="items.meta.per_page"
        :total="items.meta.total"
        :sibling-count="1"
        show-edges
        :default-page="items.meta.current_page"
    >
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <PaginationFirst />
            <PaginationPrev />

            <template v-for="(item, index) in items">
                <PaginationListItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                    <Button class="h-9 w-9 p-0" :variant="item.value === page ? 'default' : 'outline'">
                        {{ item.value }}
                    </Button>
                </PaginationListItem>
                <PaginationEllipsis v-else :key="item.type" :index="index" />
            </template>

            <PaginationNext />
            <PaginationLast />
        </PaginationList>
    </Pagination>
</template>
