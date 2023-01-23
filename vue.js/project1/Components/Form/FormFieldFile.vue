<template>
    <input type="file"
           :name="name"
           :id="name"
           ref="file_input"
           @input="form[name] = $event.target.files[0]"
           class="sr-only" >

    <button @click.prevent="choose_file"
            class="border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        Բեռնել
    </button>
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
