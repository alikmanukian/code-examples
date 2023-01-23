<template>
    <input type="checkbox"
           v-model="form[name]"
           :name="name"
           :id="name"
           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded -order-1 mr-2 mt-1">
</template>

<script setup>
import {ref} from 'vue';
const file_input = ref();

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

function choose_file() {
    file_input.value.click();
}
</script>
