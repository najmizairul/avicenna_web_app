<template>
    <app-layout title="Patients">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Patient - {{ patient.name }}
                </h2>

                <div class="flex justify-between items-center">
<!--                    <Button class="mx-1" type="button" v-if="visit?.status === 'waiting'" @click.prevent="seePatient">See Patient</Button>-->
                    <Button class="mx-1" type="button" @click.prevent="showMedicationHistory = true">View Medication History</Button>
                    <Button class="mx-1" type="button"  @click.prevent="showDiagnosisHistory = true">View Diagnosis History</Button>
                </div>


            </div>

        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex justify-between">
                        <div  class="p-4 w-2/5">
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
                                    <td class="border-t-0 px-6  border-l-0 border-r-0 text-md whitespace-nowrap p-4">{{ patient.sex }}</td>
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
                            <form @submit.prevent="prescribe">


                                <div class="py-3">
                                    <jet-label for="diagnosis" value="Diagnosis" />
                                    <textarea id="diagnosis" rows="5" v-model="form.diagnosis" required  class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"></textarea>
                                    <jet-input-error :message="form.errors.diagnosis" />
                                </div>

                                <div class="py-3">
                                    <jet-label for="search" value="Search for Drug" />
                                    <jet-input type="text" @keydown="searchDrugs" id="search" placeholder="Search Medication" v-model="query" class="mt-1 block w-full"></jet-input>

                                    <div class="py-2">
                                        <a class="block mt-1 shadow p-3 cursor-pointer" @click="selectDrug(result)" v-for="(result, key) in searchResults" :key="key">
                                            {{ result.name }}
                                        </a>
                                    </div>

                                    <div class="py-3">
                                        <jet-input-error :message="form.errors.prescriptions" />
                                    </div>

                                    <div class="p-3 shadow mt-1" v-show="showForm">
                                        <div class="pb-3">
                                            <h3>
                                                {{ prescription.name }}
                                            </h3>
                                            <div class="flex justify-between">
                                                <div class="py-2">
                                                    <jet-label for="dosage" value="Dosage" />
                                                    <jet-input  type="text" id="dosage" placeholder="eg. 10mg" v-model="prescription.dosage"  class="mt-1 block w-full"/>
                                                </div>

                                                <div class="py-2">
                                                    <jet-label for="daily_dose" value="Daily Dose" />
                                                    <jet-input type="text" id="daily_dose" placeholder="eg. 3" v-model="prescription.daily_dose"  class="mt-1 block w-full"/>
                                                </div>
                                            </div>

                                            <div class="flex justify-between">
                                                <div class="py-2">
                                                    <jet-label for="duration" value="Duration" />
                                                    <jet-input type="text" id="duration" placeholder="eg. 10" v-model="prescription.duration"  class="mt-1 block w-full" />
                                                </div>

                                                <div class="py-2">
                                                    <jet-label for="starts_at" value="Start From" />
                                                    <jet-input id="starts_at" type="date" placeholder="eg. 2026-10-02" v-model="prescription.starts_at"  class="mt-1 block w-full"/>
                                                </div>
                                            </div>

                                            <div class="py-2">
                                                <Button type="button" @click="savePrescription">Save</Button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="shadow p-2 mt-1" v-if="form.prescriptions.length > 0">
                                        <div class="flex justify-between mt-1" v-for="(p, key) in form.prescriptions" :key="key">
                                            <div>
                                                {{ p.name}}
                                            </div>

                                            <div>
                                                {{p.dosage}}
                                            </div>

                                            <div>
                                                <Button type="button" @click="removePrescription(p)">Delete</Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="py-3">
                                    <Button class="bg-indigo-500 text-white active:bg-indigo-600" type="submit">Finish Consultation</Button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <modal :show="showMedicationHistory" @close="showMedicationHistory=false" class="p-6 my-8">
            <div class="p-5">
                <h3>Shows Medication History</h3>

                <div class="overflow-y-hidden overflow-x-auto sm:rounded-lg flex justify-between">
                    <div  class="p-2">
                        <table class="table-auto border-separate items-center bg-transparent w-full border-collapse ">
                            <thead>
                            <tr>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    SN
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Previous Prescription
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Prescribed by
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Date of Prescription
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(prescription, key) in medicationHistory" :key="key">
                                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-blueGray-700 ">
                                    {{  (key + 1)}}
                                </th>
                                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 ">
                                    <span v-for="(drug, key) in prescription.drugs" :key="key" class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-blue-600 bg-blue-200 uppercase last:mr-0 mr-1">{{ drug}}</span>
                                </td>

                                <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ prescription.doctor }}
                                </td>
                                <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ prescription?.prescribed_at }}
                                </td>
                            </tr>

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </modal>

        <modal :show="showDiagnosisHistory" @close="showDiagnosisHistory=false" class="p-6 my-4">
            <div class="p-5">
                <h3>Shows Diagnosis History</h3>

                <div class="overflow-y-hidden overflow-x-auto sm:rounded-lg flex justify-between">
                    <div  class="p-2">
                        <table class="table-auto border-separate items-center bg-transparent w-full border-collapse">
                            <thead>
                            <tr>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    SN
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Previous Diagnosis
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Diagnosed by
                                </th>
                                <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    Date of Diagnosis
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="(diagnosis, key) in previousDiagnosisHistory" :key="key">
                                <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-blueGray-700 ">
                                    {{ (key + 1)}}
                                </th>
                                <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 ">
                                    {{ diagnosis.diagnosis }}
                                </td>

                                <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ diagnosis.doctor }}
                                </td>
                                <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                    {{ diagnosis?.diagnosed_at }}
                                </td>
                            </tr>

                            </tbody>

                        </table>
                    </div>
                </div>
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

    data() {
        return {
            form:  this.$inertia.form({
                visit_id:  this.visit.id,
                diagnosis: null,
                patient_id: this.patient.id,
                prescriptions :[],
            }),
            query: null,
            showForm: false,
            prescription: this.defaultPrescriptionObject(),
            showDiagnosisHistory: false,
            showMedicationHistory: false,
        }
    },

    props: ['patient', 'visit', 'drugs', 'previousDiagnosisHistory', 'medicationHistory'],

    methods: {
        seePatient() {
            this.form.visit_id =  this.visit.id;

           this.form.post(route('doctors.patients.see'), {
               onFinish: () => this.form.reset()
           })
        },

        savePrescription() {
            this.form.prescriptions.push(this.prescription)
            this.prescription = this.defaultPrescriptionObject()
            this.showForm = false

        },

        prescribe() {
            this.form.post(this.route('doctors.patients.finishConsultation', { code: this.patient.rn }),  {
                onFinish: () => this.form.reset('form'),
            });
        },

        removePrescription(prescription) {
            this.form.prescriptions.splice(this.form.prescriptions.findIndex(p => p === prescription), 1)
        },

        selectDrug(drug) {
            this.showForm = true
            this.prescription.drug_id = drug.id
            this.prescription.name = drug.name

            this.query= null

        },

        searchDrugs: debounce(() => {}, 500),

        defaultPrescriptionObject() {
            return  {
                drug_id: null,
                name: null,
                dosage: null,
                daily_dose: 0,
                duration: 0,
                starts_at: null,
            }
        }


    },

    computed: {
        searchResults() {
            if(!this.query) return [];
            return this.drugs.filter( drug => drug.name.toLowerCase().includes(this.query.toLowerCase())) || []
        }
    },

})
</script>
