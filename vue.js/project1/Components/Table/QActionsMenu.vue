<template>
    <q-confirmation
        ref="confirmationWindow"
        title="Delete User"
        content="Are you sure you want to delete user?"
        confirm-button="Yes"
        cancel-button="No"
    />

    <Menu as="div" class="w-full">
        <MenuButton class="w-full py-2 flex items-center justify-center text-gray-400 hover:text-gray-600">
            <DotsHorizontalIcon class="w-5" />
        </MenuButton>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems
                class="absolute right-0 z-20 min-w-[150px] mt-2 mr-1 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
                <div class="px-1 py-1">
                    <template v-for="(action, index) in actions">
                        <div v-if="action.type === 'Separator'" class="border-b border-gray-100 h-[1px] my-1"></div>
                        <MenuItem v-else v-slot="{ active }" :key="index">
                            <component
                                :is="action.type"
                                :href="replaceParams(action)"
                                :method="action.method"
                                :as="action.method === 'post' ? 'button' : 'a'"
                                @before="() => openConfirmation(action)"
                                v-on="{click: action.click}"
                                :class="[
                                  active ? 'bg-indigo-500 text-white' : 'text-gray-700',
                                  'group flex rounded-md items-center w-full px-2 py-2 text-sm',
                                ]"
                            >
                                <component :is="action.icon"
                                    :active="active"
                                    class="w-5 h-5 mr-2 text-indigo-400 group-hover:text-white"
                                    aria-hidden="true"
                                />
                                {{ action.label }}
                            </component>
                        </MenuItem>
                    </template>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { DotsHorizontalIcon } from '@heroicons/vue/solid';
import QConfirmation from '@shared/Components/QConfirmation.vue';
import {ref} from "vue";

const props = defineProps({
    data: {
        type: Object,
        default: null,
    },
    actions: {
        type: Array,
        default: []
    }
});

function replaceParams({href, replace}) {
    if (href) {
        replace.forEach((param) => href = href.replace(`_${param}_`, props.data[param]));
        return href;
    }
    return null;
}

const confirmationWindow = ref(null);
window.confirmationWindow = confirmationWindow;

function openConfirmation(action) {
    if (!action.confirmation) return true;
    return confirmationWindow.value.open();
}

</script>
