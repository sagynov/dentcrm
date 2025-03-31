<script setup lang="ts">
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { ref } from 'vue';

const page = usePage<SharedData>();
const locale = ref(page.props.locale);
const available_locales = ref(page.props.available_locales);

const flags = (current_locale: any) => {
    return [
        {
            'fi fi-ru': current_locale == 'ru',
            'fi fi-us': current_locale == 'en',
            'fi fi-kz': current_locale == 'kk',
        },
        'mr-2',
    ];
};
</script>

<template>
    <DropdownMenu class="w-full">
        <DropdownMenuTrigger> <span :class="flags(locale)"></span> {{ trans('' + locale) }} </DropdownMenuTrigger>
        <DropdownMenuContent>
            <DropdownMenuItem v-for="item_locale in available_locales" :key="item_locale">
                <a :href="route('set-locale', { locale: item_locale })" class="w-full">
                    <span :class="flags(item_locale)"></span>
                    <span>
                        {{ trans('' + item_locale) }}
                    </span>
                </a>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
