<script setup lang="ts">
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData, type User } from '@/types';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { BriefcaseMedical, CalendarDays, HandHeart, Handshake, LayoutGrid, ShoppingBag, Stethoscope } from 'lucide-vue-next';
import { ref } from 'vue';

const items: NavItem[] = [
    {
        title: trans('Dashboard'),
        href: '/owner',
        icon: LayoutGrid,
    },
    {
        title: trans('My clinics'),
        href: '/owner/clinics',
        icon: BriefcaseMedical,
    },
    {
        title: trans('Services'),
        href: '/owner/services',
        icon: ShoppingBag,
    },
    {
        title: trans('Appointments'),
        href: '/owner/appointments',
        icon: Handshake,
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
const user = page.props.auth.user as User;
const clinics = user.clinics as Array<any>;

const form = useForm({
    active_clinic: '',
});

const active_clinic = ref(user.active_clinic);
const selectClinic = (value: any) => {
    form.active_clinic = value;
    form.post(route('set-active-clinic'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>{{ trans('Clinic') }}</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem class="my-2">
                <Select @update:model-value="selectClinic" v-model="active_clinic">
                    <SelectTrigger>
                        <SelectValue :placeholder="trans('Select')" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="clinic in clinics" :key="'clinic_' + clinic.id" :value="clinic.id">
                            {{ clinic.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </SidebarMenuItem>
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
