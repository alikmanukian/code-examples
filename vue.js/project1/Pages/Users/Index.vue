<template>
    <Head :title="__('users')"/>

    <layout>
        <template #header>{{ __('users') }}</template>

        <q-table :headers="tableHeadings"
                   :entries="props.data"
                   :actions="actions"
                   :batchActions="batchActions"
                   stickyHeader
                   sortable
                   fixedPagination
        >
            <template #column.avatar="{value, className}">
                <td :class="className" class="w-[50px] min-w-[50px] sm:w-[40px] sm:min-w-[40px] !pl-0 !pr-0">
                    <img class="w-full rounded-full block m-auto" :src="value"/>
                </td>
            </template>

            <template #column.name="{value, className, row}">
                <td :class="className" class="sm:w-auto sm:max-w-none">
                    <Link :href="route('admin.users.show', {'user': row.id})" class="font-medium text-indigo-600">{{ value }}</Link>

                    <dl class="font-normal lg:hidden">
                        <dt class="sr-only sm:hidden">{{ row.email }}</dt>
                        <dd class="mt-1 truncate text-gray-500 sm:hidden">{{ row.email }}</dd>
                    </dl>
                </td>
            </template>

            <template #column.email="{value, className}">
                <td :class="className" class="hidden sm:table-cell">{{ value }}</td>
            </template>

            <template #column.status="{value, className}">
                <td :class="className" class="hidden sm:table-cell w-24">
                    <div class="uppercase py-1 px-4 rounded-full text-center text-xs"
                         :class="value === 'active' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800'"
                    >{{ value }}
                    </div>
                </td>
            </template>
        </q-table>
    </layout>
</template>

<script setup>
import filter from 'lodash/filter';
import Layout from '@admin/Layouts/Auth.vue';
import QTable from '@shared/Components/Table/QTable.vue';

import {
    PencilIcon,
    TrashIcon
} from '@heroicons/vue/solid';

const props = defineProps({
    data: Object
});

const tableHeadings = [
    {
        field: 'avatar',
        label: '',
        sortable: false,
    },
    {
        field: 'name',
        label: __('name'),
    },
    {
        field: 'email',
        label: __('email'),
        class: 'hidden sm:table-cell'
    },
    {
        field: 'status',
        label: __('status'),
        class: 'hidden sm:table-cell',
    },

];

const actions = [
    {
        type: 'Link',
        label: 'Edit',
        icon: PencilIcon,
        href: route('admin.users.show', {'user':'_id_'}),
        replace: ['id']
    },
    {
        type: 'QConfirmationLink',
        label: 'Delete',
        icon: TrashIcon,
        href: route('admin.users.destroy', {'user':'_id_'}),
        replace: ['id'],
        method: 'post',
        batch: true,
        confirmation: {title: __("Delete Users"), message: __("Are you sure you want to delete user?")}
    }
];

const batchActions = filter(actions, {batch: true});
</script>
