<script setup lang="ts">
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { SharedData, User } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';
import Locale from './Locale.vue';
import NavDoctor from './NavDoctor.vue';
import NavOwner from './NavOwner.vue';
import NavPatient from './NavPatient.vue';

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
            <NavOwner v-if="user.is_owner" />
            <NavDoctor v-if="user.is_doctor" />
            <NavPatient v-if="user.is_patient" />
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
