<template>
    <Link :href="sortUrl(column)"
          class="w-full block flex items-center px-2 sm:px-3"
          :class="{'bg-gray-200': sortDirection(column) === 'asc' || sortDirection(column) === 'desc'}"
    >
        <span class="flex-auto py-3"
              :class="sortDirection(column) === 'asc' || sortDirection(column) === 'desc' ? 'font-bold text-black' : ''"><slot></slot></span>
        <span class="flex-none">
            <i :class="sortDirection(column) === 'asc' ? 'text-gray-800' : 'text-gray-400'">
                <ChevronUpIcon class="w-4 h-4" />
            </i>
            <i :class="sortDirection(column) === 'desc' ? 'text-gray-800' : 'text-gray-400'">
                <ChevronDownIcon class="w-4 h-4" />
            </i>
        </span>
    </Link>
</template>

<script setup>
import {computed} from "vue";
import {
    ChevronDownIcon,
    ChevronUpIcon
} from '@heroicons/vue/solid';

const props = defineProps({
    column: String,
});

const sortParam = computed(() => {
    const urlSearchParams = new URLSearchParams(window.location.search);
    const params = Object.fromEntries(urlSearchParams.entries());
    return params.sort;
});

function sortDirection(column) {
    if (sortParam.value) {
        if (sortParam.value === column) return 'asc';
        if (sortParam.value === '-' + column) return 'desc';
    }
}

function sortUrl(column){
    const urlSearchParams = new URLSearchParams(window.location.search);

    if (!sortParam.value) {
        urlSearchParams.set('sort', column);
    } else if (sortDirection(column) === 'asc') {
        urlSearchParams.set('sort', '-' + column);
    } else if (sortDirection(column) === 'desc') {
        urlSearchParams.delete('sort');
    } else {
        urlSearchParams.set('sort', column);
    }
    let paramsString = urlSearchParams.toString() ?? '';
    paramsString = paramsString ? `?${paramsString}` : '';
    return window.location.origin + window.location.pathname + paramsString;
}

</script>
