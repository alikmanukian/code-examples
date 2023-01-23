<template>
    <date-picker v-model="form[name]" is24hr color="blue" locale="hy-AM" mode="date">
        <template v-slot="{ inputValue, togglePopover }">
            <input
                :class="{'!border-red-500 focus:!ring-red-500 focus:!border-red-500': form.errors[name]}"
                class="py-2 px-3 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:ring-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                :value="inputValue"
                @click.stop="dateSelected($event, date, togglePopover)"
            />
        </template>
    </date-picker>
</template>

<script setup>
import { DatePicker } from 'v-calendar';
import 'v-calendar/dist/style.css';

defineProps({
    name: {
        type: String,
        required: true
    },
    form: {
        type: Object,
        required: true
    },
});

    function dateSelected(e, date, toggle) {
        this.selected = date;
        toggle({ ref: e.target });
    }
</script>
