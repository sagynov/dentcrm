import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
}

export interface User {
    id: number;
    name: string;
    phone: string;
    is_owner?: boolean;
    is_doctor?: boolean;
    is_patient?: boolean;
    active_clinic: string;
    clinics: Array<any>;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Patient {
    id: number;
    first_name: string;
    last_name: string;
    full_name: string;
    birth_date: any;
    joined_at: any;
}

export type BreadcrumbItemType = BreadcrumbItem;
