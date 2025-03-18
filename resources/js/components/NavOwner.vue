<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { LayoutGrid, GraduationCap, HandHeart, Stethoscope, CalendarDays, BriefcaseMedical } from 'lucide-vue-next';

const items: NavItem[] = [
    {
        title: trans('Dashboard'),
        href: '/owner',
        icon: LayoutGrid,
    },
    {
        title: trans('My Clinics'),
        href: '/owner/clinics',
        icon: BriefcaseMedical,
    },
    {
        title: trans('Schedule'),
        href: '/owner/schedule',
        icon: CalendarDays,
    },
    {
        title: trans('Doctors'),
        href: '/owner/doctors',
        icon: Stethoscope,
    },
    {
        title: trans('Patients'),
        href: '/owner/patients',
        icon: HandHeart,
    },
];

const page = usePage<SharedData>();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>{{ trans('Owner') }}</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton as-child :is-active="item.href === page.url">
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
