<template>
    <button type="submit"
            :disabled="disabled || loading"
            :class="{'opacity-25 cursor-not-allowed': disabled || loading}"
            class="flex justify-center w-full disabled py-2 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <i class="w-12" :class="{'hidden': !loading}">
            <svg version="1.1" id="L4" xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink"
                 viewBox="0 0 100 50" xml:space="preserve">
                <circle fill="#fff" stroke="none" cx="6" cy="25" r="6">
                    <animate
                        attributeName="opacity"
                        dur="1s"
                        values="0;1;0"
                        repeatCount="indefinite"
                        begin="0.1"/>
                </circle>
                <circle fill="#fff" stroke="none" cx="26" cy="25" r="6">
                    <animate
                        attributeName="opacity"
                        dur="1s"
                        values="0;1;0"
                        repeatCount="indefinite"
                        begin="0.2"/>
                </circle>
                <circle fill="#fff" stroke="none" cx="46" cy="25" r="6">
                    <animate
                        attributeName="opacity"
                        dur="1s"
                        values="0;1;0"
                        repeatCount="indefinite"
                        begin="0.3"/>
               </circle>
            </svg>
        </i>
        <div :class="{'hidden': loading}">
            <slot>Button</slot>
        </div>
    </button>
</template>

<script setup>
import {ref} from 'vue';

const props = defineProps({
    loading: Boolean,
    disabled: Boolean
});
</script>
