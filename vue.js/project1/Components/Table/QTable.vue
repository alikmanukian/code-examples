<template>
    <div class="bg-white border" :class="{'flex-auto': fixedPagination}">
        <slot name="header"></slot>
        <slot v-if="entries.length === 0" name="empty"></slot>

        <div v-else :class="{'overflow-x-auto': !stickyHeader, 'pb-[55px]': fixedPagination}">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full border-separate divide-y divide-gray-300"
                       style="border-spacing: 0"
                >
                    <thead class="bg-gray-50 text-left text-xs font-medium uppercase text-gray-500 tracking-wide"
                           :class="{'sticky top-[50px] z-10 bg-opacity-75 backdrop-blur backdrop-filter sm:top-0': stickyHeader}"
                    >
                        <tr>
                            <slot v-if="batchActions.length" name="header.checkbox">
                                <th scope="col" class="relative w-12 px-6 sm:w-16 sm:px-8 border-b border-gray-300">
                                    <input ref="checkAll" @change="checkAllRows" type="checkbox" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 sm:left-6 appearance-none">
                                </th>
                            </slot>

                            <slot v-for="header in headers"
                                  :name="`header.${header.field}`"
                                  :field="header.field"
                                  :label="header.label"
                            >
                                <th scope="col"
                                    class="border-b border-gray-300"
                                    :class="header.class"
                                >
                                    <q-table-sorter v-if="sortable && (header.sortable !== false)"
                                                 :column="header.field">{{ header.label }}</q-table-sorter>
                                    <div v-else class="py-3 px-2 sm:px-3">{{ header.label }}</div>
                                </th>
                            </slot>

                            <slot v-if="actions.length" name="header.actions">
                                <th scope="col" class="border-b border-gray-300"></th>
                            </slot>
                        </tr>
                    </thead>

                    <slot name="tbody" :rows="rows">
                        <tbody class="bg-white" :class="tbodyClass">
                            <tr :class="[...trClass, checkedRows.includes(row.id) ? 'bg-indigo-50' : '']"
                                v-for="(row, rowIndex) in rows"
                                :key="rowIndex"
                            >
                                <slot name="column.checkbox" v-if="batchActions.length" :className="tdClass">
                                    <td :class="tdClass" class="w-4 relative">
                                        <!-- Selected row marker, only show when row is selected. -->
                                        <div class="absolute inset-y-0 left-0 w-1 bg-indigo-700" v-if="checkedRows.includes(row.id)"></div>
                                        <input type="checkbox"
                                               class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6"
                                               v-model="checkedRows"
                                               :value="row.id"
                                        >
                                    </td>
                                </slot>

                                <slot v-for="(column, columnIndex) in headers"
                                      :name="`column.${column.field}`"
                                      :field="column.field"
                                      :row="row"
                                      :value="row[column.field]"
                                      :index="columnIndex"
                                      :key="columnIndex"
                                      :className="tdClass"
                                >
                                    <td :class="tdClass">
                                        {{ row[column.field] }}
                                    </td>
                                </slot>

                                <slot name="actions" v-if="actions.length" :className="tdClass">
                                    <td :class="tdClass" class="w-12 !p-0 relative">
                                        <q-actions-menu :actions="actions" :data="row"></q-actions-menu>
                                    </td>
                                </slot>
                            </tr>
                        </tbody>
                    </slot>
                </table>
            </div>
        </div>

        <slot name="footer"></slot>

        <q-pagination v-if="entries.data && entries.meta.last_page > 1"
                    :links="entries.meta.links"
                    :class="[fixedPagination ? 'fixed left-0 bottom-0 right-0 ml-0 md:ml-64 bg-white pb-3 sm:pb-5 z-10 bg-opacity-75 backdrop-blur backdrop-filter' : 'mb-3 sm:mb-6']"
        ></q-pagination>
    </div>
</template>

<script setup>
import map from 'lodash/map';
import {computed, ref, watch} from "vue";
import QPagination from '@shared/Components/Table/QPagination.vue';
import QTableSorter from '@shared/Components/Table/QTableSorter.vue';
import QActionsMenu from '@shared/Components/Table/QActionsMenu.vue';

const props = defineProps({
    headers: {
        type: Array,
        default: []
    },
    entries: {
        type: Object,
        default: {}
    },
    actions: {
        type: Array,
        default: []
    },
    tbodyClass: {
        type: String,
        default: ''
    },
    trClass: {
        type: String,
        default: ''
    },
    stickyHeader: {
        type: Boolean,
        default: false,
    },
    compact: {
        type: Boolean,
        default: false,
    },
    sortable: {
        type: Boolean,
        default: false,
    },
    batchActions: {
        type: Array,
        default: []
    },
    fixedPagination: {
        type: Boolean,
        default: false
    }
});

const checkAll = ref();
const rows = computed(() => props.entries.data);
const tdClass = 'px-2 sm:px-3 text-sm text-gray-500 border-b border-gray-200' + (props.compact ? ' py-2' : ' py-4');

const checkedRows = ref([]);

// single ref
watch(checkedRows, (newX) => {
    if (newX.length === 0) {
        checkAll.value.checked = 0;
        checkAll.value.indeterminate = false;
    } else if (newX.length > 0 && newX.length === rows.value.length) {
        checkAll.value.checked = 1;
        checkAll.value.indeterminate = false;
    } else {
        checkAll.value.checked = 0;
        checkAll.value.indeterminate = true;
    }
})

function checkAllRows() {
    if (checkAll.value.checked) {
        checkedRows.value = map(rows.value, (row) => row.id);
    } else {
        checkedRows.value = [];
    }
}
</script>
