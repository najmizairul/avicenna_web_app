<!-- This example requires Tailwind CSS v2.0+ -->
<template>
    <div class="p-6 shadow shadow-xl">
        <div>
            <nav class="sm:hidden" aria-label="Back">
                <Link :href="route('patients.prescriptions')" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <ChevronLeftIcon class="flex-shrink-0 -ml-1 mr-1 h-5 w-5 text-gray-400" aria-hidden="true" />
                    Back
                </Link>
            </nav>
            <nav class="hidden sm:flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <div class="flex">
                            <Link :href="route('patients.prescriptions')"  class="text-sm font-medium text-gray-500 hover:text-gray-700">Dashboard</Link>
                        </div>
                    </li>

                    <li v-if="false">
                        <div class="flex items-center">
                            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />
                            <a href="#" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Engineering</a>
                        </div>
                    </li>
<!--                    <li>-->
<!--                        <div class="flex items-center">-->
<!--                            <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />-->
<!--                            <a href="#" aria-current="page" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Back End Developer</a>-->
<!--                        </div>-->
<!--                    </li>-->
                </ol>
            </nav>
        </div>
        <div class="mt-2 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ title || 'Dashboard' }}
                </h2>
            </div>
            <div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
                <slot name="buttons">

                    <div class="flex items-center">

                        <span class="font-medium"> Hi, {{ user }}</span>
                        <button @click="logout" type="button" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Logout
                        </button>
                    </div>

                </slot>
            </div>
        </div>
    </div>

    <main>
        <slot></slot>
    </main>
</template>

<script setup>
import { computed } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import { Link, usePage } from '@inertiajs/inertia-vue3';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/solid'
import { useScreenSafeArea } from '@vueuse/core'

const {
    top,
    right,
    bottom,
    left,
} = useScreenSafeArea()

const props = defineProps({
    title: String,
});

const user = computed(() => usePage().props.value.user.name)

function logout() {
    Inertia.post(route('patients.logout'))
}
</script>
