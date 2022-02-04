<template>
    <app-layout title="Orders">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Prescription for {{ patient?.name }}
                </h2>

<!--                <Button v-if="visit?.status === 'waiting'" @click.prevent="seePatient">See Patient</Button>-->
<!--                <Button @click.prevent="">Show QRCode</Button>-->
                <Button v-show="qrCode" @click.prevent="showQrCode = true">Show QRCode</Button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex justify-between">


                        <div  class="p-4 w-2/5">

                            <h1 class="text-xl my-2">Patient</h1>
                            <table class="items-center bg-transparent w-full border-collapse shadow">
                                <tbody>
                                <tr>
                                    <th class="border-t-1 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4">Full Name:</th>
                                    <td class="border-t-0 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.name }}</td>
                                </tr>

                                <tr>
                                    <th class="border-t-1 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4">Email:</th>
                                    <td class="border-t-0 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.email }}</td>
                                </tr>

                                <tr>
                                    <th class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">IC Number:</th>
                                    <td class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.ic }}</td>
                                </tr>

                                <tr>
                                    <th class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">Registered Number:</th>
                                    <td class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.rn }}</td>
                                </tr>

                                <tr>
                                    <th class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">Phone:</th>
                                    <td class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.phone }}</td>
                                </tr>

                                <tr>
                                    <th class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">Address:</th>
                                    <td class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.address }}</td>
                                </tr>

                                <tr>
                                    <th class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">Gender:</th>
                                    <td class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4 capitalize">{{ patient.sex }}</td>
                                </tr>

                                <tr>
                                    <th class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">Date of Birth:</th>
                                    <td class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.dob }}</td>
                                </tr>

                                <tr>
                                    <th class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">Last Visit:</th>
                                    <td class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.last_visited_at }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class=" p-4 w-2/5">
                            <div class=" p-4">
                                <h1 class="text-xl my-2">About Prescription</h1>
                                <table class="items-start bg-transparent w-full border-collapse shadow">
                                    <tbody>

                                    <tr v-if="prescription.code">
                                        <th class="border-t-1 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4">Code:</th>
                                        <td class="border-t-0 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4 capitalize flex justify-between items-center">
                                            {{ prescription.code }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="border-t-1 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4">Status:</th>
                                        <td class="border-t-0 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4 capitalize flex justify-between items-center">
                                            <span>{{ prescription.status }}</span>
                                            <span v-if="prescription.status === 'active'"><Button @click.prevent="ready">It's Ready</Button></span>
                                            <span v-if="prescription.status === 'ready'"><Button @click.prevent="filled">Filled</Button></span>
                                        </td>
                                    </tr>

                                    <tr v-if="prescription.collect_at">
                                        <th class="border-t-1 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4">Collect At:</th>
                                        <td class="border-t-0 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4 capitalize flex justify-between items-center">
                                            <span class="mr-2">{{ prescription.collect_at ?? 'Not set yet' }} </span>
                                            <span v-if="!isFilled">
                                                <Button v-if="!showForm" @click.prevent="showForm = true">Change</Button>
                                            </span>
                                        </td>
                                    </tr>

                                    <tr v-if="prescription.collected_at">
                                        <th class="border-t-1 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4">Collected At:</th>
                                        <td class="border-t-0 px-6 border-l-0 border-r-0 text-md whitespace-nowrap p-4 capitalize">{{ prescription.collected_at ?? 'Not yet collected' }}</td>
                                    </tr>
                                    </tbody>
                                </table>

                                <form class="mt-3" @submit.prevent="inform" v-if="(showForm && !isFilled) && allDrugsInStock">
                                    <div class="py-3">
                                        <jet-label for="collect_at" value="Collect At" />
                                        <jet-input id="collect_at" type="datetime-local" v-model="form.collect_at" required class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"></jet-input>
                                        <jet-input-error :message="form.errors.collect_at" />
                                    </div>

                                    <div class="py-3">
                                        <Button class="bg-indigo-500 text-white active:bg-indigo-600" type="submit">Inform Patient</Button>
                                    </div>
                                </form>
                            </div>

                            <div class=" p-4">
                                <h1 class="text-xl my-2">Prescribed Drugs</h1>

                                <div v-for="(drug, key) in prescription.drugs" :key="key">
                                    <p class=" border border-1 px-6 p-4 my-1">
                                        <strong>{{ drug.dosage }}</strong> of <strong>{{ drug.drug.name }}</strong>,
                                        <br>Take  <strong>{{ drug.daily_dose }}</strong> per day for <strong>{{ drug.duration }}</strong> days
                                        <span v-if="prescription.status !== 'ready' &&  prescription.status !== 'filled'">
                                            <br> {{ (drug.daily_dose * drug.duration) < drug.drug.quantity ? "Has enough in Stock" : "Needs " + ( (drug.daily_dose * drug.duration) - drug.drug.quantity) + " More pills to Fulfil Order" }}
                                        </span>

                                    </p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <modal :show="showQrCode" @close="showQrCode=false" v-show="qrCode">
            <div class="aspect-w-1 aspect-h-1 p-6 my-8" >
                <img :src="qrCode" alt="">
            </div>
        </modal>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input.vue";
import Modal from "@/Jetstream/Modal.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import debounce from 'lodash.debounce';

export default defineComponent({
    components: {
        Button,
        JetInput,
        JetLabel,
        AppLayout,
        Modal,
        JetInputError
    },

    props: ['prescription'],

    data() {
        return {
            showForm: this.prescription.status === 'unfilled',
            showQrCode: false,
            form:  this.$inertia.form({
                status:  this.prescription.status !== 'unfilled' ? this.prescription.status : 'active',
                collect_at: this.prescription.collect_at ?? null,
                collected_at: this.prescription.collected_at ?? null,
            }),
        }
    },

    computed: {
        patient() {
            return this.prescription.patient
        },

        isFilled() {
            return this.prescription.status === 'filled';
        },

        qrCode() {
            if(!this.prescription.code) return null;
            return 'https://api.qrserver.com/v1/create-qr-code?qr=1&size=500x500&data=' + route('pharmacy.orders.code', this.prescription.code)
        },

        outOfStock() {
           return this.prescription.drugs.filter(drug => (drug.daily_dose * drug.duration) > drug.drug.quantity);
        },

        allDrugsInStock() {
            return this.outOfStock.length === 0
        }
    },

    methods: {
        updatePrescription() {
            this.form.post(route('pharmacy.orders.update', { id: this.prescription.id }),  {
                onFinish: () => {
                    this.showForm = false
                    this.form.reset('form')
                }
            });
        },

        inform() {
            this.form.status = 'active';
            this.updatePrescription()
        },

        ready() {
            this.form.status = 'ready';
            this.updatePrescription()
        },

        filled() {
            this.form.status = 'filled';
            this.updatePrescription()
        }
    }
})
</script>
