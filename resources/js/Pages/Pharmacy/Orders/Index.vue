<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Orders
            </h2>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-if="prescriptions.data.length > 0">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                            <div class="mt-8 text-2xl flex justify-between items-center">
                                <h1 class="pt-5">Prescriptions</h1>

                                <div class="flex items-center">
                                    <Filter class="mr-2" default="all" by="status" :options="['all', 'ready', 'unfilled', 'active', 'filled', 'rejected']" />
                                    <Search class=" pt-5" :route="route('pharmacy.orders')" />
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
                                                Patient Name
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Prescribed by
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Status
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Drug Count
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Date
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Action
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr v-for="(prescription, key) in prescriptions.data" :key="key">
                                            <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-blueGray-700 ">
                                                {{ (prescriptions.current_page * prescriptions.per_page) + (key + 1) - prescriptions.per_page }}
                                            </th>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 ">
                                                {{ prescription?.patient?.name }} ({{ prescription?.patient?.ic }})
                                            </td>
                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ prescription?.user?.name }}
                                            </td>
                                            <td class="capitalize border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ prescription?.status }}
                                            </td>
                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ prescription.drugs.length }}
                                            </td>

                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ prescription?.status === 'unfilled' ? prescription.created_at : prescription.collect_at }}
                                            </td>

                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                <Link class="p-2 m-1" :href="route('pharmacy.orders.show', { id: prescription.id })">View</Link>
<!--                                                <Link class="p-2 m-1" v-if="!prescription.code && prescription.status === 'unfilled'" :href="route('pharmacy.orders.accept', { id: prescription.id })">Accept</Link>-->
                                            </td>
                                        </tr>

                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <Pager :model="prescriptions" />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Pager from "@/Jetstream/Pager";
    import JetLabel from "@/Jetstream/Label";
    import { Link } from "@inertiajs/inertia-vue3";
    import Filter from "@/Jetstream/Filter";
    import Search from "@/Jetstream/Search";

    export default defineComponent({
        props: ['prescriptions'],

        components: {
            Pager,
            Link,
            Filter,
            JetLabel,
            AppLayout,
            Search
        },
    })
</script>
