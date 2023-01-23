<template>
    <Head :title="__('post')"/>

    <layout>
        <template #header>{{ __('post') }}</template>

        <div class="bg-white p-6">

            <form class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="col-span-6 sm:col-span-3">
                    <label for="title" class="block text-sm font-medium text-gray-700">{{ __('title') }}</label>
                    <div class="mt-1">
                        <input type="text"
                               v-model="form.title"
                               name="title"
                               id="title"
                               autocomplete="title"
                               :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors.title}"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <p class="text-red-500 text-xs mt-1 ml-1">{{ form.errors.title }}</p>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <label for="code" class="block text-sm font-medium text-gray-700">{{ __('code') }}</label>
                    <div class="mt-1">
                        <q-list-box></q-list-box>
                    </div>
                </div>

                <div class="col-span-6 sm:col-span-1">
                    <label for="status" class="block text-sm font-medium text-gray-700">{{ __('status') }}</label>
                    <div class="mt-1">
                        <input type="text"
                               v-model="form.status"
                               name="status"
                               id="status"
                               autocomplete="status"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="pt-4 col-span-6">
                    <label class="block text-sm font-medium text-gray-700">{{ __('content') }}</label>
                    <div class="mt-1">
                        <editor
                            api-key="zsu28rxuvjfyse3rdhfcm5enjkeb28tulou07okuzpwy6cx5"
                            :init="{
                                 height: 500,
                                 menubar: true,
                                 plugins: [
                                   'a11ychecker','advlist','advcode','advtable','autolink','checklist','export',
                                   'lists','link','image','charmap','preview','anchor','searchreplace','visualblocks',
                                   'powerpaste','fullscreen','formatpainter','insertdatetime','media','table','help','wordcount'
                                 ],
                                 toolbar:
                                   'undo redo | casechange blocks | bold italic backcolor | \
                                   alignleft aligncenter alignright alignjustify | \
                                   bullist numlst checklist outdent indent | removeformat | a11ycheck code table help'
                               }"
                            v-model="form.content"
                        />
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="button"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </layout>
</template>

<script setup>
import {useForm} from '@inertiajs/inertia-vue3'
import Layout from '@admin/Layouts/Auth.vue';
import Editor from '@tinymce/tinymce-vue'
import QListBox from '@shared/Components/QListBox.vue';

const props = defineProps({
    data: Object
})

const form = useForm({...props.data.data})

</script>
