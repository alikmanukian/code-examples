<template>
    <input type="text"
           v-model="form[name]"
           :name="name"
           :id="name"
           :autocomplete="autocomplete ? name : null"
           :placeholder="placeholder"
           :class="{'!border-red-500 focus:!ring-red-500 focus:!border-red-500': form.errors[name]}"
           class="py-2 px-3 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md">
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
    placeholder: String,
    autocomplete: Boolean,
    required: Boolean, // don't delete (need vor backend validation)
});
</script>
