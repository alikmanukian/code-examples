<template>
    <q-modal ref="modal" v-slot="{close}">
        <slot name="title">
            <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900"
            >
                {{ title }}
            </DialogTitle>
        </slot>

        <slot name="content">
            <DialogDescription class="mt-2 modal-content">
                <p class="text-sm text-gray-500">
                    {{ content }}
                </p>
            </DialogDescription>
        </slot>

        <slot name="buttons">
            <div class="mt-8 flex space-x-2">
                <button
                    type="button"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-blue-900 bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
                    @click="confirm"
                >
                    {{ confirmButton || 'Ok' }}
                </button>

                <button
                    type="button"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-red-400 border border-red-400 rounded-md hover:bg-red-200 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
                    @click="cancel"
                >
                    {{ cancelButton || 'Cancel' }}
                </button>
            </div>
        </slot>

    </q-modal>
</template>

<script setup>
import QModal from '@shared/Components/QModal.vue';
import {
    DialogTitle,
    DialogDescription
} from '@headlessui/vue';
import {ref} from "vue";

const resolvePromise = ref();
const modal = ref();

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    content: {
        type: String,
        required: true
    },
    confirmButton: {
        type: String
    },
    cancelButton: {
        type: String
    }
});

function open() {
    modal.value.open();

    return new Promise((resolve) => {
        resolvePromise.value = resolve;
    });
}

function confirm() {
    modal.value.close();
    resolvePromise.value(true);
}

function cancel() {
    modal.value.close();
    resolvePromise.value(false);
}

defineExpose({
    open
})
</script>
