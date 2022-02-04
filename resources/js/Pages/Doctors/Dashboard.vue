<template>
    <app-layout title="Dashboard">
        <template class="flex justify-between items-center" #header>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>

            <div class="flex justify-between items-center">
                <div class="flex justify-between items-center">
                    <svg class="w-6 h-6 mr-1" :class="$page.props.user.is_available ? 'text-green-700' : 'text-red-700'"  fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                    <span>{{ $page.props.user.is_available ? 'Online' : 'Offline' }}</span>
                </div>

                <Button @click.prevent="toggleAvailability">Toggle Availability</Button>
            </div>
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
                                        <h5>Pantai Hospital Batu Pahat -  Doctors App</h5>
                                    </div>
                                    <div class="div">
                                        <Link :href="route('doctors.patients')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">View Patients</Link>
                                    </div>
                                </div>

                                <div class="flex">
                                    <div class=" sm:grid sm:h-32 sm:grid-flow-row sm:gap-4 sm:grid-cols-3">
                                        <div id="jh-stats-positive" class="flex flex-col justify-center px-4 py-4 bg-white border border-gray-300 rounded">
                                            <div>
                                                <!--                                        <div>-->
                                                <!--                                            <p class="flex items-center justify-end text-green-500 text-md">-->
                                                <!--                                                <span class="font-bold">6%</span>-->
                                                <!--                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path class="heroicon-ui" d="M20 15a1 1 0 002 0V7a1 1 0 00-1-1h-8a1 1 0 000 2h5.59L13 13.59l-3.3-3.3a1 1 0 00-1.4 0l-6 6a1 1 0 001.4 1.42L9 12.4l3.3 3.3a1 1 0 001.4 0L20 9.4V15z"/></svg>-->
                                                <!--                                            </p>-->
                                                <!--                                        </div>-->
                                                <p class="text-3xl font-semibold text-center text-gray-800"> {{ patientsWaiting }}</p>
                                                <p class="text-lg text-center text-gray-500">Patients Waiting</p>
                                            </div>
                                        </div>

                                        <div id="jh-stats-negative" class="flex flex-col justify-center px-4 py-4 mt-4 bg-white border border-gray-300 rounded sm:mt-0">
                                            <div>

                                                <p class="text-3xl font-semibold text-center text-gray-800">{{ patientsToday }}</p>
                                                <p class="text-lg text-center text-gray-500">Patients Today</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    import {Link} from "@inertiajs/inertia-vue3";

    export default defineComponent({
        props: ['stats'],

        components: {
            Button,
            Link,
            AppLayout,
        },

        computed: {
            patientsWaiting() {
                return this.stats.patientsWaiting;
            },

            patientsToday() {
                return  this.stats.patientsToday;
            }
        },

        methods: {
            toggleAvailability() {
                this.$inertia.post(route('toggleAvailability'))
            }
        }
    })
</script>
