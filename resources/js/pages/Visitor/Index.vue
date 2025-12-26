<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head, Link, router} from '@inertiajs/vue3';
import {visitorsCreate, visitorsDestroy, visitorsEdit, visitorsIndex} from "@/routes";
import {Button} from "@/components/ui/button";
import {Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue,} from '@/components/ui/select';
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
import {ChevronLeft, ChevronRight, Pen, SearchIcon, Trash, X} from 'lucide-vue-next';
import {Input} from "@/components/ui/input";
import {ref} from "vue";

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

interface PaginationLink {
    url: string | null
    label: string
    active: boolean
}

interface PaginatedData<T> {
    data: T[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    from: number
    to: number
    links: PaginationLink[]
}

const {visitors, filters} = defineProps<{
    visitors: PaginatedData<Visitor>;
    filters: { per_page: number, search: string };
}>();

const perPage = ref(filters.per_page);
const searchQuery = ref(filters.search ?? '')

const searchVisitors = () => {
    router.get(visitorsIndex().url, {
        search: searchQuery.value,
        per_page: perPage.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const clearSearch = () => {
    searchQuery.value = '';
    router.get(visitorsIndex().url, {
        per_page: perPage.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const changePerPage = (value: string | number) => {
    perPage.value = Number(value)
    
    router.get(visitorsIndex().url, {
        page: 1,
        per_page: perPage.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

</script>

<template>
    <Head title="Список посетителей" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col gap-4 p-4">
            
            <div v-if="visitors.total === 0" class="text-center">
                <span>Посетителей пока нет.&nbsp;
                    <Link :href="visitorsCreate().url" class="cursor-pointer p-0 font-normal text-md underline">Добавить</Link>
                </span>
            </div>
            
            <div v-else class="flex flex-col h-full gap-4">
                <!--Верхняя панель-->
                <div class="flex items-center gap-4 flex-shrink-0">
                    <Button variant="outline">Настройка таблицы</Button>
                    
                    <div class="flex w-full max-w-sm items-center space-x-2">
                        <div class="relative flex-1">
                            <Input v-model="searchQuery" type="text" placeholder="Поиск..." @keyup.enter="searchVisitors" class="pr-8" />
                            <button v-if="searchQuery" @click="clearSearch" class="absolute right-2 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground">
                                <X class="h-4 w-4" />
                            </button>
                        </div>
                        <Button @click="searchVisitors" :disabled="!searchQuery" variant="outline">
                            <SearchIcon class="h-4 w-4" />
                        </Button>
                    </div>
                    
                    <Button @click="router.get(visitorsCreate().url)" class="ml-auto">Добавить</Button>
                </div>
                
                <!--Таблица-->
                <div class="rounded-md border flex-1 overflow-hidden flex flex-col">
                    <div class="overflow-y-auto flex-1">
                        <Table>
                            <TableHeader class="sticky top-0 bg-white">
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
                                <TableRow v-for="(visitor, index) in visitors.data" :key="visitor.id">
                                    <TableCell class="font-medium">{{ visitors.from + index }}</TableCell>
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
                
                <!--Нижняя панель с пагинацией-->
                <div class="flex justify-between items-center flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Записей на странице:</span>
                        <Select :model-value="String(perPage)" @update:model-value="value => changePerPage(value as string | number)">
                            <SelectTrigger class="w-[80px]">
                                <SelectValue placeholder="Количество записей" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="5">5</SelectItem>
                                    <SelectItem value="10">10</SelectItem>
                                    <SelectItem value="20">20</SelectItem>
                                    <SelectItem value="50">50</SelectItem>
                                    <SelectItem value="100">100</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="flex items-center gap-1">
                        <Button
                            size="icon"
                            variant="ghost"
                            :disabled="!visitors.links[0].url"
                            @click="visitors.links[0].url && router.visit(visitors.links[0].url)"
                        >
                            <ChevronLeft class="w-4 h-4" />
                        </Button>
                        
                        <Button
                            v-for="(link, i) in visitors.links.slice(1, -1)"
                            :key="i"
                            size="sm"
                            :variant="link.active ? 'outline' : 'ghost'"
                            :disabled="!link.url"
                            @click="link.url && router.visit(link.url)"
                        >
                            {{ link.label }}
                        </Button>
                        
                        <Button
                            size="icon"
                            variant="ghost"
                            :disabled="!visitors.links.at(-1)?.url"
                            @click="() => {
                                const lastLink = visitors.links.at(-1)
                                if (lastLink?.url) {
                                    router.visit(lastLink.url, { preserveScroll: true, preserveState: true })
                                }
                            }"
                        >
                            <ChevronRight class="w-4 h-4" />
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
