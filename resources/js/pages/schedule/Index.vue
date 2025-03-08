<script setup lang="ts">
import { Calendar } from '@/components/ui/calendar';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('Schedule'),
        href: '/schedule',
    },
];
const days = ['06', '07', '08', '09', '10', '11', '12'];
const schedules = [
  {
    cabinet: 'Кабинет 1',
    patient: 'Ержан Серікбай 10:00',
  },
  {
    cabinet: 'Кабинет 2',
    patient: 'Ержан Серікбай 10:00',
  },
  {
    cabinet: 'Кабинет 3',
    patient: 'Ержан Серікбай 10:00',
  },
];
</script>

<template>
    <Head :title="trans('Schedule')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-12">
                <div class="col-span-12 md:col-span-8">
                    <div class="border rounded-lg">
                    <Table class="">
                        <TableCaption>A list of your recent invoices.</TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="border-r w-[150px]">{{ trans('Cabinet') }}</TableHead>
                                <TableHead v-for="day in days" :key="day" class="border-x w-[150px]">{{ day }}</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="schedule in schedules" :key="schedule.cabinet">
                                <TableCell class="font-medium">
                                    {{ schedule.cabinet }}
                                </TableCell>
                                <TableCell class="bg-green-600 text-white">{{ schedule.patient }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-4">
                    <div>
                        <Calendar :weekday-format="'short'" class="rounded-md border" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
