<template>
    <app-layout title="Patients">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Patients
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-if="patients.data.length > 0">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                            <div class="mt-8 text-2xl flex justify-between items-center">
                                <h1 class="pt-5">Patients</h1>

                                <div class="flex items-center">
                                    <Search class=" pt-5" :route="route('doctors.patients')" />
                                </div>

                            </div>

                            <div class="mt-6 text-gray-500" >
                                <div class="block w-full overflow-x-auto">
                                    <table class="items-center bg-transparent w-full border-collapse ">
                                        <thead>
                                        <tr>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                SN
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Full Name
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                IC Number
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Referrer
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Last Visit
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Action
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr v-for="(patient, key) in patients.data" :key="key">
                                            <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-blueGray-700 ">
                                                {{ (patients.current_page * patients.per_page) + (key + 1) - patients.per_page }}
                                            </th>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 ">
                                                {{ patient.name }}
                                            </td>

                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ patient.ic }}
                                            </td>
                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ patient?.referrer }}
                                            </td>

                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ patient.last_visited_at }}
                                            </td>

                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                <Link :href="route('doctors.patients.show', { code: patient.rn })">See Patient</Link>
                                            </td>
                                        </tr>

                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <Pager :model="patients" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from "@/Jetstream/Button.vue";
import { Link } from "@inertiajs/inertia-vue3";
import Pager from "@/Jetstream/Pager";
import Search from "@/Jetstream/Search";

export default defineComponent({
    props: ['patients'],

    components: {
        Button,
        Link,
        AppLayout,
        Pager,
        Search
    },
})
</script>
