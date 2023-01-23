<template>
    <textarea
           v-model="form[name]"
           :name="name"
           :id="name"
           :placeholder="placeholder"
           :class="{'!border-red-500 focus:!ring-red-500 focus:!border-red-500': form.errors[name]}"
           class="h-24 py-2 px-3 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md">
    </textarea>
</template>

<script setup>
defineProps({
    name: {
        type: String,
        required: true
    },
    form: {
        type: Object,
        required: true
    },
    required: Boolean,
    placeholder: String,
});
</script>
