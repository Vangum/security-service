<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import {Sidebar, SidebarContent, SidebarFooter,} from '@/components/ui/sidebar';
import {dashboard, departmentsIndex, visitorsIndex} from '@/routes';
import {type NavItem} from '@/types';
import {Folder, ChartSpline, File, Users} from 'lucide-vue-next';

const mainNavItems: NavItem[] = [
    {
        title: 'Посетители',
        href: visitorsIndex(),
        icon: Users,
    },
    {
        title: 'Справочники',
        href: departmentsIndex(),
        icon: File,
    },
    {
        title: 'Дашборды',
        href: dashboard(),
        icon: ChartSpline,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/Vangum/security-service',
        icon: Folder,
    }
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>
        
        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
