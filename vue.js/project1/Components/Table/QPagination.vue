<template>
    <nav class="border-t border-gray-200 px-4 flex items-center justify-between sm:px-0">
        <div class="-mt-px w-0 flex-1 flex pl-4 sm:pl-6">
            <component :is="links.prev.url ? 'Link' : 'span'"
                       :href="links.prev.url"
                       class="border-t-2 border-transparent pt-4 pr-1 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                <ArrowNarrowLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                {{ __('previous') }}
            </component>
        </div>
        <div class="hidden md:-mt-px md:flex">
            <component v-for="link in links.pages"
                       :is="link.label === '...' ? 'span' : 'Link'"
                       :href="link.url"
                       class="border-t-2 pt-4 px-4 inline-flex items-center text-sm font-medium"
                       :class="{'border-indigo-500 text-indigo-600 font-bold': link.active, 'border-transparent text-gray-500': !link.active}">
                {{ link.label }}
            </component>
        </div>
        <div class="-mt-px w-0 flex-1 flex justify-end pr-4 sm:pr-6">
            <component :is="links.next.url ? 'Link' : 'span'"
                       :href="links.next.url"
                       class="border-t-2 border-transparent pt-4 pl-1 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                {{ __('next') }}
                <ArrowNarrowRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
            </component>
        </div>
    </nav>
</template>

<script setup>
import { ArrowNarrowLeftIcon, ArrowNarrowRightIcon } from '@heroicons/vue/solid';

const props = defineProps({
    links: Array
});

const links = {
    'prev': props.links[0],
    'next': props.links.slice(-1)[0],
    'pages': props.links.slice(1, -1)
}
</script>
