<template>
    <app-layout title="Show Prescription">
        <Head title="Show Prescription" />


        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Prescription Information
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            Personal details and prescription details.
                        </p>
                    </div>

                </div>

                <div>
                    <Button v-if="!prescription.is_patient_coming" @click.prevent="onWay" class="mr-2">I'm on my way</Button>
                    <Button v-show="qrCode" @click.prevent="showQrCode = true">Show QRCode</Button>
                </div>

            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Full name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ patient.name }} ({{ patient.ic }})
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Status
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="flex-shrink-0 flex">
                                <p class="capitalize px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"
                                   :class="{
                                        'bg-purple-100 text-purple-800': prescription.status === 'ready',
                                        'bg-orange-100 text-orange-800': prescription.status === 'unfilled',
                                        'bg-green-100 text-green-800': prescription.status === 'filled'
                                    }"
                                >
                                    {{ prescription.status ?? 'N/A' }}
                                </p>
                            </div>
                        </dd>
                    </div>


                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Prescription Code
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ prescription.code }}
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Prescribed By
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 capitalize">
                            {{ prescription.user.name }} ({{ prescription.user.department }})
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            {{ prescription.collected_at ? ' Collected on ' : ' Collect on ' }}
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <time :datetime="prescription.collected_at ? prescription.collected_at : prescription.collect_at">
                                {{ prescription.collected_at ? prescription.collected_at : prescription.collect_at }}
                            </time>
                        </dd>
                    </div>

                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Drugs
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">

                                <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm" v-for="(drug, key) in prescription.drugs" :key="key">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <ArchiveIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 bg-gray-100 p-0.5" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ drug.dosage }} {{ drug.drug.manufacturer }} {{ drug.drug.name }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Take {{ drug.daily_dose }} per day for {{ drug.duration}} days
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                Start taking from {{ drug.starts_at }}
                                            </p>
                                        </div>
                                        <div>
                                            <!--                                        <a href="#" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">-->
                                            <!--                                            View-->
                                            <!--                                        </a>-->
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <modal :show="showQrCode" @close="showQrCode=false"  v-show="qrCode">
            <div class="p-2 my-2">
                <img :src="qrCode" class="w-full h-auto" alt="QR Code">
            </div>
        </modal>
    </app-layout>
</template>

<script setup>
// @ is an alias to /src
import {
    SquareGeofenceRegion,
} from "../../../Services/Geofence";
import { ref, computed, onMounted } from "vue";
import { Head, Link } from '@inertiajs/inertia-vue3';
import Button from "@/Jetstream/Button.vue";
import Modal from "@/Jetstream/Modal.vue";
import { Inertia } from "@inertiajs/inertia";

import {ArchiveIcon } from '@heroicons/vue/solid';
import AppLayout from "@/Layouts/Patients/AppLayout";

import { useQRCode } from '@vueuse/integrations/useQRCode'
import { usePermission, useGeolocation } from '@vueuse/core'

const { isSupported } = useGeolocation()

const geolocationAccess = usePermission('geolocation');

const props = defineProps({
    prescription: Object,
    patient: Object,
})

const showQrCode = ref(false)

const qrCode = useQRCode(route('pharmacy.orders.code', props.prescription.code))

function onWay() {
    Inertia.post(route('pharmacy.orders.update.onway', {id: props.prescription.id }))
}

function inFence() {
    Inertia.post(route('pharmacy.orders.update.infence', {id: props.prescription.id }));
}


function track() {

    const fenceA = new SquareGeofenceRegion({
        name: "Hospital Pantai Batu Pahat",
        latitude: 59.345635,
        longitude: 18.059707,
        axis: 0.02, // meters
    });

    const fenceB = new SquareGeofenceRegion({
        name: "dammy's house",
        latitude: 51.76438782019234,
        longitude: -1.1971338457413676,
        axis: 0.02, // meters in all 4 directions
    });

    const fenceC = new SquareGeofenceRegion({
        name: "Najmi's house",
        latitude: 1.841400862929456,
        longitude: 102.959384602445,
        axis: 0.02, //
    })

    const fenceD = new SquareGeofenceRegion({
        name: "UiTM Jasin Melaka",
        latitude: 1.841400862929456,
        longitude: 102.959384602445,
        axis: 0.02, //
    })

    const fences = [fenceA, fenceB, fenceC];
    const options = {
        enableHighAccuracy: true,
        // timeout: 5000,
        maximumAge: 0
    };

    if(! isSupported ) return;

    const watcher = navigator.geolocation.watchPosition(
        ({ coords }) => {
            for (const fence of fences) {
                const lat = coords.latitude;
                const lon = coords.longitude;

                if (fence.inside(lat, lon)) {
                    // do some logic
                    alert("Now in geofence named  " + fence.name)

                    inFence()

                    navigator.geolocation.clearWatch(watcher)
                }
            }
        },
        console.error,
        options
    );
}

onMounted(() => track());
</script>

<!--"drugs": [-->
<!--    "drug_id": 30,-->
<!--    "prescription_id": 23,-->
<!--    "dosage": "1 mg",-->
<!--    "daily_dose": 3,-->
<!--    "duration": 17,-->
<!--    "starts_at": "20 Jan 2022",-->
<!--    "drug": {-->
<!--        "id": 30,-->
<!--        "name": "incidunt",-->
<!--        "manufacturer": "Kohler-Weber",-->
<!--        "sku": "RO81UAWS4Q5YNUZ09OZM05V0",-->
<!--        "quantity": 85,-->
<!--        "expires_at": "12 Jan 2023, 07:15PM",-->
<!--        "created_at": "12 Jan 2022, 07:15PM",-->
<!--        "updated_at": "12 Jan 2022, 07:15PM"-->
<!--    }-->
<!--]-->
