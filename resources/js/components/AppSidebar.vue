<script setup lang="ts">
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarGroup, SidebarGroupContent, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { SharedData, User, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, Stethoscope, CalendarDays, HandHeart } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { trans } from 'laravel-vue-i18n';
import NavOwner from './NavOwner.vue';
import NavDoctor from './NavDoctor.vue';
import Locale from './Locale.vue';

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavDoctor v-if="user.is_doctor" />
            <NavOwner v-if="user.is_owner" />
        </SidebarContent>

        <SidebarFooter>
            <SidebarGroup class="p-4">
                <SidebarGroupContent>
                    <SidebarMenu>
                        <SidebarMenuItem class="w-full">
                            <Locale />
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroupContent>
            </SidebarGroup>
            
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
