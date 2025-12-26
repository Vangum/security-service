<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger
} from "@/components/ui/alert-dialog";
import {Button} from '@/components/ui/button';
import {Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle} from '@/components/ui/dialog';
import {Field, FieldError, FieldLabel} from '@/components/ui/field';
import {Input} from '@/components/ui/input';
import {InputGroup, InputGroupAddon, InputGroupInput} from '@/components/ui/input-group';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/components/ui/table';
import {Pen, SearchIcon, Trash} from 'lucide-vue-next';
import {departmentsDestroy, departmentsIndex, departmentsStore, departmentsUpdate, visitorsCreate} from "@/routes";
import {ref} from "vue";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Справочники',
        href: departmentsIndex().url,
    },
];

interface Department {
    id: number;
    name: string;
}

const {departments} = defineProps<{
    departments: Department[];
}>();

const showCreateDialog = ref(false);
const showEditDialog = ref(false);

const createForm = useForm({
    name: '',
});

const editForm = useForm({
    id: null as number | null,
    name: '',
});

const openCreateDialog = () => {
    createForm.reset();
    createForm.clearErrors();
    showCreateDialog.value = true;
};

const openEditDialog = (department: Department) => {
    editForm.clearErrors();
    editForm.id = department.id;
    editForm.name = department.name;
    showEditDialog.value = true;
};

const createDepartment = () => {
    createForm.post(departmentsStore().url, {
        onSuccess: () => {
            showCreateDialog.value = false;
            createForm.reset();
        },
    });
};

const updateDepartment = () => {
    if (editForm.id === null) return;
    
    editForm.put(departmentsUpdate(editForm.id).url, {
        onSuccess: () => {
            showEditDialog.value = false;
            editForm.reset();
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Справочники" />
        
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            
            <div v-if="departments.length === 0" class="text-center">
                <span>Справочников пока нет.&nbsp;
                    <Button @click="openCreateDialog" variant="link" class="cursor-pointer p-0 font-normal text-md underline">Добавить</Button>
                </span>
            </div>
            
            <div v-else>
                <div class="flex items-center gap-4 mb-4">
                    <InputGroup class="max-w-sm">
                        <InputGroupInput placeholder="Поиск..." />
                        <InputGroupAddon>
                            <SearchIcon />
                        </InputGroupAddon>
                    </InputGroup>
                    <Button @click="openCreateDialog" class="ml-auto">Добавить</Button>
                </div>
                <div class="rounded-md border px-2">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[100px]">ID</TableHead>
                                <TableHead>Название элемента справочника</TableHead>
                                <TableHead class="text-right">Действия</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="department in departments" :key="department.id">
                                <TableCell class="font-medium">{{ department.id }}</TableCell>
                                <TableCell>{{ department.name }}</TableCell>
                                <TableCell class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Button @click="openEditDialog(department)" variant="outline" size="icon" aria-label="Submit">
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
                                                        Это действие удалит отдел. Вы действительно хотите продолжить?
                                                    </AlertDialogDescription>
                                                </AlertDialogHeader>
                                                <AlertDialogFooter>
                                                    <AlertDialogCancel>Отмена</AlertDialogCancel>
                                                    <AlertDialogAction @click="router.delete(departmentsDestroy(department).url)">Удалить</AlertDialogAction>
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
            <Dialog v-model:open="showCreateDialog">
                <DialogContent class="sm:max-w-[525px]">
                    <form @submit.prevent="createDepartment">
                        <DialogHeader>
                            <DialogTitle>Добавить справочник</DialogTitle>
                            <DialogDescription> Пожалуйста, введите название справочника. После завершения нажмите «Сохранить».</DialogDescription>
                        </DialogHeader>
                        <Field class="my-4" :data-invalid="!!createForm.errors.name">
                            <FieldLabel for="name"> Название*</FieldLabel>
                            <Input id="name" v-model="createForm.name" :aria-invalid="!!createForm.errors.name" placeholder="Введите название" />
                            <FieldError v-if="createForm.errors.name" :errors="[createForm.errors.name]" />
                        </Field>
                        <DialogFooter>
                            <Button type="submit"> Сохранить</Button>
                            <DialogClose as-child>
                                <Button variant="outline"> Отмена</Button>
                            </DialogClose>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
            <Dialog v-model:open="showEditDialog">
                <DialogContent class="sm:max-w-[525px]">
                    <form @submit.prevent="updateDepartment">
                        <DialogHeader>
                            <DialogTitle>Редактировать справочник</DialogTitle>
                            <DialogDescription> Пожалуйста, измените название справочника. После завершения нажмите «Сохранить».</DialogDescription>
                        </DialogHeader>
                        <Field class="my-4" :data-invalid="!!editForm.errors.name">
                            <FieldLabel for="name"> Название*</FieldLabel>
                            <Input id="name" v-model="editForm.name" :aria-invalid="!!createForm.errors.name" placeholder="Введите название" />
                            <FieldError v-if="editForm.errors.name" :errors="[editForm.errors.name]" />
                        </Field>
                        <DialogFooter>
                            <Button type="submit"> Сохранить</Button>
                            <DialogClose as-child>
                                <Button variant="outline"> Отмена</Button>
                            </DialogClose>
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
