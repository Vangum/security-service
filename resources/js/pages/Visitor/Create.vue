<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {type BreadcrumbItem} from '@/types';
import {Head, useForm} from '@inertiajs/vue3';
import {visitorsCreate} from "@/routes";
import {Button} from '@/components/ui/button'
import {RadioGroup, RadioGroupItem,} from '@/components/ui/radio-group'
import {Field, FieldDescription, FieldGroup, FieldLabel, FieldLegend, FieldSet,} from '@/components/ui/field'
import {Input} from '@/components/ui/input'
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue,} from '@/components/ui/select'
import {Textarea} from '@/components/ui/textarea'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Добавить посетителя',
        href: visitorsCreate().url,
    },
];

const form = useForm({
    full_name: '',
    department: '',
    birth_date: '',
    position: '',
    phone: '',
    
    document_type: 'passport',
    passport_series: '',
    passport_number: '',
    passport_issue_date: '',
    passport_issued_by: '',
    passport_department_code: '',
    
    license_series_number: '',
    license_issue_date: '',
    license_region: '',
    license_issued_by: '',
    
    other_document_name: '',
    other_series_number: '',
    other_issue_date: '',
    other_issued_by: '',
    
    entry_datetime: '',
    exit_datetime: '',
    remarks: '',
})
</script>

<template>
    <Head title="Добавить посетителя" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-4xl">
                <form>
                    <FieldGroup>
                        <FieldSet>
                            
                            <FieldLegend>Добавить посетителя</FieldLegend>
                            <FieldDescription>
                                Пожалуйста, заполните все необходимые поля для добавления посетителя.
                            </FieldDescription>
                            
                            <FieldGroup class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--ФИО-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="full_name">ФИО*</FieldLabel>
                                    <Input v-model="form.full_name" id="full_name" required />
                                </Field>
                                <!--Отдел-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel>Отдел*</FieldLabel>
                                    <Select v-model="form.department" required>
                                        <SelectTrigger>
                                            <SelectValue placeholder="Выберите отдел" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="engineering">
                                                Engineering
                                            </SelectItem>
                                            <SelectItem value="design">
                                                Design
                                            </SelectItem>
                                            <SelectItem value="marketing">
                                                Marketing
                                            </SelectItem>
                                            <SelectItem value="sales">
                                                Sales
                                            </SelectItem>
                                            <SelectItem value="support">
                                                Customer Support
                                            </SelectItem>
                                            <SelectItem value="hr">
                                                Human Resources
                                            </SelectItem>
                                            <SelectItem value="finance">
                                                Finance
                                            </SelectItem>
                                            <SelectItem value="operations">
                                                Operations
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </Field>
                                <!--Дата рождения-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="birth_date">Дата рождения*</FieldLabel>
                                    <Input v-model="form.birth_date" id="birth_date" type="date" required />
                                </Field>
                                <!--Должность-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="position">Должность*</FieldLabel>
                                    <Input v-model="form.position" id="position" required />
                                </Field>
                                <!--Телефон-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="phone">Номер телефона*</FieldLabel>
                                    <Input v-model="form.phone" id="phone" type="tel" required />
                                </Field>
                            </FieldGroup>
                        </FieldSet>
                        <FieldSet>
                            <FieldLegend>Документ, удостоверяющий личность</FieldLegend>
                            <FieldDescription>
                                Пожалуйста, выберите один из предложенных вариантов.
                            </FieldDescription>
                            <RadioGroup v-model="form.document_type" class="mt-4 flex gap-12">
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="passport" value="passport" />
                                    <Label for="passport">Паспорт</Label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="license" value="license" />
                                    <Label for="license">Водительское удостоверение</Label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="other" value="other" />
                                    <Label for="other">Прочее</Label>
                                </div>
                            </RadioGroup>
                        </FieldSet>
                        
                        <FieldSet :class="form.document_type === 'passport' ? 'mb-10' : 'mb-35'">
                            <!--Поля при выборе паспорта-->
                            <FieldGroup v-if="form.document_type === 'passport'" class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--Серия-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="passport_series">Серия*</FieldLabel>
                                    <Input v-model="form.passport_series" id="passport_series" required />
                                </Field>
                                <!--Номер-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="passport_number">Номер*</FieldLabel>
                                    <Input v-model="form.passport_number" id="passport_number" required />
                                </Field>
                                <!--Дата выдачи-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="passport_issue_date">Дата выдачи*</FieldLabel>
                                    <Input v-model="form.passport_issue_date" id="passport_issue_date" type="date" required />
                                </Field>
                                <!--Кем выдан-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="passport_issued_by">Кем выдан*</FieldLabel>
                                    <Input v-model="form.passport_issued_by" id="passport_issued_by" required />
                                </Field>
                                <!--Код подразделения-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="passport_department_code">Код подразделения*</FieldLabel>
                                    <Input v-model="form.passport_department_code" id="passport_department_code" required />
                                </Field>
                            </FieldGroup>
                            
                            <!--Поля при выборе водительского-->
                            <FieldGroup v-if="form.document_type === 'license'" class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--Серия и номер-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="license_series_number">Серия и номер*</FieldLabel>
                                    <Input v-model="form.license_series_number" id="license_series_number" required />
                                </Field>
                                <!--Дата выдачи-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="license_issue_date">Дата выдачи*</FieldLabel>
                                    <Input v-model="form.license_issue_date" id="license_issue_date" type="date" required />
                                </Field>
                                <!--Регион-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="license_region">Регион*</FieldLabel>
                                    <Input v-model="form.license_region" id="license_region" required />
                                </Field>
                                <!--Кем выдан-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="license_issued_by">Кем выдан*</FieldLabel>
                                    <Input v-model="form.license_issued_by" id="license_issued_by" required />
                                </Field>
                            </FieldGroup>
                            
                            <!--Поля при выборе другого документа-->
                            <FieldGroup v-if="form.document_type === 'other'" class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--Название документа-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="other_document_name">Название документа*</FieldLabel>
                                    <Input v-model="form.other_document_name" id="other_document_name" required />
                                </Field>
                                <!--Серия и номер-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="other_series_number">Серия и номер*</FieldLabel>
                                    <Input v-model="form.other_series_number" id="other_series_number" required />
                                </Field>
                                <!--Дата выдачи-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="other_issue_date">Дата выдачи*</FieldLabel>
                                    <Input v-model="form.other_issue_date" id="other_issue_date" type="date" required />
                                </Field>
                                <!--Кем выдан-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="other_issued_by">Кем выдан*</FieldLabel>
                                    <Input v-model="form.other_issued_by" id="other_issued_by" required />
                                </Field>
                            </FieldGroup>
                        </FieldSet>
                        <FieldSet>
                            <FieldGroup class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <!--Дата и время входа-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="entry_datetime">Дата и время входа*</FieldLabel>
                                    <Input v-model="form.entry_datetime" id="entry_datetime" type="datetime-local" required />
                                </Field>
                                <!--Дата и время выхода-->
                                <Field class="sm:col-span-3">
                                    <FieldLabel for="exit_datetime">Дата и время выхода*</FieldLabel>
                                    <Input v-model="form.exit_datetime" id="exit_datetime" type="datetime-local" required />
                                </Field>
                            </FieldGroup>
                            <FieldGroup>
                                <!--Замечание-->
                                <Field>
                                    <FieldLabel for="remarks">Замечание</FieldLabel>
                                    <Textarea v-model="form.remarks" id="remarks" class="resize-none" />
                                </Field>
                            </FieldGroup>
                        </FieldSet>
                        <Field orientation="horizontal">
                            <Button type="submit">
                                Сохранить
                            </Button>
                            <Button variant="outline" type="button">
                                Отмена
                            </Button>
                        </Field>
                    </FieldGroup>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
