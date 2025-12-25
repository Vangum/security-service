<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head, Link, router} from '@inertiajs/vue3';
import {visitorsCreate, visitorsDestroy, visitorsEdit, visitorsIndex} from "@/routes";
import {Button} from "@/components/ui/button";
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import {Pen, SearchIcon, Trash, X} from 'lucide-vue-next';
import {Input} from "@/components/ui/input";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Список посетителей',
        href: visitorsIndex().url,
    },
];

interface Department {
    id: number;
    name: string;
}

interface Document {
    type: string;
    type_label: string;
    passport_series?: string;
    passport_number?: string;
    license_series_number?: string;
    other_series_number_original?: string;
}

interface Visitor {
    id: number,
    full_name: string;
    department: Department;
    birth_date_formatted: string;
    position: string;
    document: Document;
    phone: string;
    entry_datetime_formatted: string;
    exit_datetime_formatted: string;
    remarks: string | null;
}

const {visitors} = defineProps<{
    visitors: Visitor[];
}>();

</script>

<template>
    <Head title="Список посетителей" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            
            <div v-if="visitors.length === 0" class="text-center">
                <span>Посетителей пока нет.&nbsp;
                    <Link :href="visitorsCreate().url" class="cursor-pointer p-0 font-normal text-md underline">Добавить</Link>
                </span>
            </div>
            
            <div v-else>
                <div class="flex items-center gap-4 mb-4">
                    <Button variant="outline">Настройка таблицы</Button>
                    
                    <div class="flex w-full max-w-sm items-center space-x-2">
                        <div class="relative flex-1">
                            <Input type="text" placeholder="Поиск..." class="pr-8" />
                            <button class="absolute right-2 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground">
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                        <Button variant="outline">
                            <SearchIcon class="h-4 w-4" />
                        </Button>
                    </div>
                    <Button @click="router.get(visitorsCreate().url)" class="ml-auto">Добавить</Button>
                </div>
                
                <div class="rounded-md border mb-4">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[100px]">№</TableHead>
                                <TableHead>Дата и время входа</TableHead>
                                <TableHead>Дата и время выхода</TableHead>
                                <TableHead>ФИО</TableHead>
                                <TableHead>Дата рождения</TableHead>
                                <TableHead>Должность</TableHead>
                                <TableHead>Отдел</TableHead>
                                <TableHead>Телефон</TableHead>
                                <TableHead>Документ</TableHead>
                                <TableHead>Замечание</TableHead>
                                <TableHead class="text-right">Действия</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(visitor, index) in visitors" :key="visitor.id">
                                <TableCell class="font-medium">{{ index + 1 }}</TableCell>
                                <TableCell>{{ visitor.entry_datetime_formatted }}</TableCell>
                                <TableCell>{{ visitor.exit_datetime_formatted }}</TableCell>
                                <TableCell>{{ visitor.full_name }}</TableCell>
                                <TableCell>{{ visitor.birth_date_formatted }}</TableCell>
                                <TableCell>{{ visitor.position }}</TableCell>
                                <TableCell>{{ visitor.department.name }}</TableCell>
                                <TableCell>{{ visitor.phone }}</TableCell>
                                <TableCell>
                                    <template v-if="visitor.document">
                                        {{ visitor.document.type_label }}
                                        <br>
                                        <template v-if="visitor.document.type === 'passport'">
                                            {{ visitor.document.passport_series }} {{ visitor.document.passport_number }}
                                        </template>
                                        
                                        <template v-else-if="visitor.document.type === 'license'">
                                            {{ visitor.document.license_series_number }}
                                        </template>
                                        
                                        <template v-else>
                                            {{ visitor.document.other_series_number_original }}
                                        </template>
                                    </template>
                                    
                                    <template v-else>–</template>
                                </TableCell>
                                <TableCell>{{ visitor.remarks ?? '–' }}</TableCell>
                                <TableCell class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        
                                        <Button variant="outline" size="icon" @click="router.get(visitorsEdit(visitor.id).url)">
                                            <Pen />
                                        </Button>
                                        
                                        <AlertDialog>
                                            <AlertDialogTrigger as-child>
                                                <Button variant="outline" size="icon">
                                                    <Trash />
                                                </Button>
                                            </AlertDialogTrigger>
                                            <AlertDialogContent>
                                                <AlertDialogHeader>
                                                    <AlertDialogTitle>Вы уверены?</AlertDialogTitle>
                                                    <AlertDialogDescription>
                                                        Это действие удалит запись о посетителе. Вы действительно хотите продолжить?
                                                    </AlertDialogDescription>
                                                </AlertDialogHeader>
                                                <AlertDialogFooter>
                                                    <AlertDialogCancel>Отмена</AlertDialogCancel>
                                                    <AlertDialogAction @click="router.delete(visitorsDestroy(visitor.id).url)">Удалить</AlertDialogAction>
                                                </AlertDialogFooter>
                                            </AlertDialogContent>
                                        </AlertDialog>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
