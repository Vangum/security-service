<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head, router} from '@inertiajs/vue3';
import {visitorsCreate, visitorsIndex} from "@/routes";
import {Button} from "@/components/ui/button";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Добавить посетителя',
        href: visitorsIndex().url,
    },
];
</script>

<template>
    <Head title="Добавить посетителя" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        
        </div>
    </AppLayout>
</template>
