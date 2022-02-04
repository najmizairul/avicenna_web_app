<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                            <div class="mt-8 text-2xl">
                                Welcome {{ $page.props.user.name ?? null }}!
                            </div>

                            <div class="mt-6 text-gray-500">
                                <div class="flex justify-between items-center">
                                    <div class="div">
                                        <h5>Pantai Hospital Batu Pahat -  Pharmacy App</h5>
                                    </div>
                                    <div class="div">
                                        <Link :href="route('pharmacy.orders')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">View Orders</Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="py-3 text-xl font-medium">Patients Expected Today</h1>
                <div class="bg-white shadow overflow-hidden sm:rounded-md" >
                    <ul role="list" class="divide-y divide-gray-200" v-if="nearbyPatients.length > 0">
                        <li v-for="(prescription,key) in nearbyPatients" :key="key">
                            <Link :href="route('pharmacy.orders.show', { id: prescription.id })" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            {{ prescription.code || 'No code Assigned yet' }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <ChevronRightIcon class="w-6 h-auto text-gray-400" />
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                            {{ prescription.patient.name }}
                                            {{ prescription.is_patient_coming ? ' is coming to the hospital ' : '' }}
                                            {{ prescription.is_patient_coming && prescription.is_patient_nearby  ? 'and' : '' }}
                                            {{ prescription.is_patient_nearby ? ' is near the hospital ' : '' }}
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </li>
                    </ul>

                    <EmptyState v-else />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script setup>
    import { ChevronRightIcon } from '@heroicons/vue/solid'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Button from "@/Jetstream/Button.vue";
    import EmptyState from "@/Jetstream/EmptyState.vue";
    import {Link} from "@inertiajs/inertia-vue3";

    defineProps({
        nearbyPatients: Array
    })
</script>
