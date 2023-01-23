<template>
    <component :is="$attrs.method === 'post' ? 'button' : 'a'" @click.prevent="onClick">
        <slot></slot>
    </component>
</template>

<script setup>
import { useAttrs } from 'vue'
import { Inertia } from '@inertiajs/inertia';

const attrs = useAttrs()

async function onClick() {
    let result = true;
    if (attrs.onBefore) {
        result = await attrs.onBefore();
    }

    if (result) {
        const method = attrs.method || 'get';

        Inertia.visit(attrs.href, {
            data: method === 'get' ? {} : (attrs.data || {}),
            method: method,
            replace: attrs.replace || false,
            preserveScroll: attrs.preserveScroll || false,
            preserveState: (attrs.preserveState || false) ?? (method !== 'get'),
            only: attrs.only || [],
            headers: attrs.headers || {},
            onCancelToken: attrs.onCancelToken || (() => ({})),
            // onBefore: attrs.onBefore || (() => ({})),
            onStart: attrs.onStart || (() => ({})),
            onProgress: attrs.onProgress || (() => ({})),
            onFinish: attrs.onFinish || (() => ({})),
            onCancel: attrs.onCancel || (() => ({})),
            onSuccess: attrs.onSuccess || (() => ({})),
            onError: attrs.onError || (() => ({})),
        })
    }
}
</script>
