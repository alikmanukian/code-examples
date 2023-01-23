<template>
    <div class="form_field">
        <label :for="name" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }} <span v-if="required" class="text-red-600 text-sm">*</span>
        </label>
        <component :is="componentName" v-bind="props"/>
        <p v-if="form.errors[name]" class="text-red-400 text-xs mt-1">{{ form.errors[name] }}</p>
    </div>
</template>

<script setup>
// noinspection ES6UnusedImports
import FormFieldText from '@shared/Components/Form/FormFieldText.vue';
import FormFieldTextarea from '@shared/Components/Form/FormFieldTextarea.vue';
import FormFieldFile from '@shared/Components/Form/FormFieldFile.vue';
import FormFieldCheckbox from '@shared/Components/Form/FormFieldCheckbox.vue';
import FormFieldDatepicker from '@shared/Components/Form/FormFieldDatepicker.vue';
import {computed,watch} from 'vue';

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    name: {
        type: String,
        required: true
    },
    form: {
        type: Object,
        required: true
    },
    type: {
        type: String,
        default: 'text'
    },
    placeholder: String,
    required: Boolean,
    autocomplete: Boolean
});

const componentName = computed(() => {
    switch (props.type) {
        case 'text':
            return FormFieldText;
        case 'textarea':
            return FormFieldTextarea;
        case 'file':
            return FormFieldFile;
        case 'checkbox':
            return FormFieldCheckbox;
        case 'datepicker':
            return FormFieldDatepicker;
    }
});

</script>
