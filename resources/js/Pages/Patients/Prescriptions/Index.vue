<template>
    <app-layout title="Dashboard">
        <Head title="My Prescriptions"/>

        <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Prescriptions
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Here are all my recent prescriptions.
            </p>
        </div>

        <div>
            <div class="">
                <nav class="relative z-0 rounded-lg shadow flex divide-x divide-gray-200 capitalize" aria-label="Tabs">
                    <a @click="tabs.map(tab => tab.current = false); tab.current = true" v-for="(tab, tabIdx) in tabs" :key="tab.name" :class="[tab.current ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700', tabIdx === 0 ? 'rounded-l-lg' : '', tabIdx === tabs.length - 1 ? 'rounded-r-lg' : '', 'group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10']" :aria-current="tab.current ? 'page' : undefined">
                        <span>{{ tab.name }}</span>
                        <span aria-hidden="true" :class="[tab.current ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']" />
                    </a>
                </nav>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-md" >
            <ul role="list" class="divide-y divide-gray-200" v-if="selectedPrescription.length > 0">
                <li v-for="prescription in selectedPrescription" :key="prescription.id">
                    <Link :href="routeToPrescription(prescription)" class="block hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    {{ prescription.code || 'No code Assigned yet' }}
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="capitalize px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"
                                       :class="{
                                        'bg-purple-100 text-purple-800': prescription.status === 'ready',
                                        'bg-green-100 text-green-800': prescription.status === 'filled'
                                    }"
                                    >
                                        {{ prescription.status ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <CalendarIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                                    <p>
                                        {{ prescription.collected_at ? ' Collected on ' : ' Collect on ' }}
                                        {{ ' ' }}
                                        <time :datetime="prescription.collected_at ? prescription.collected_at : prescription.collect_at">
                                            {{ prescription.collected_at ? prescription.collected_at : prescription.collect_at }}
                                        </time>
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                       <ChevronRightIcon class="w-4 h-4" />
                                </div>
                            </div>
                        </div>
                    </Link>
                </li>
            </ul>

            <EmptyState v-else />
        </div>
    </app-layout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/inertia-vue3';
import { CalendarIcon, ChevronRightIcon, } from '@heroicons/vue/solid'
import AppLayout from "@/Layouts/Patients/AppLayout";
import EmptyState from "@/Jetstream/EmptyState.vue";

const props = defineProps({
    prescriptions: Array
})

const tabs = ref([
    { name: 'ready', current: false },
    { name: 'active', current: true },
    { name: 'filled', current: false },
])

const selectedTab = computed(() => tabs.value.find(tab => tab.current).name)

const selectedPrescription = computed(() => props.prescriptions.filter(prescription => prescription.status === selectedTab.value))

function routeToPrescription(prescription) {
   return prescription.code
       ? route('patients.prescriptions.show', { code: prescription.code })
       : '#';
}
</script>
