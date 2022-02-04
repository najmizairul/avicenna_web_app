<template>
    <app-layout title="Dashboard">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Pharmacy Drug Stock
                </h2>

                <Button @click.native="showUpdateStock = true">Add Drug</Button>
            </div>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-if="drugs.data.length > 0">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                            <div class="mt-8 text-2xl flex justify-between items-center">
                                <h1>Drugs</h1>

                                <Search :route="route('pharmacy.stock')" />
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
                                                Name
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Manufacturer
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                SKU
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Stock Left
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Expires At
                                            </th>
                                            <th class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                Action
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr v-for="(drug, key) in drugs.data" :key="key">
                                            <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-blueGray-700 ">
                                                {{ (drugs.current_page * drugs.per_page) + (key + 1) - drugs.per_page }}
                                            </th>
                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 ">
                                                {{ drug?.name }}
                                            </td>
                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ drug?.manufacturer }}
                                            </td>
                                            <td class="capitalize border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ drug?.sku }}
                                            </td>
                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ drug.quantity }}
                                            </td>

                                            <td class="border-t-0 px-6 align-center border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                {{ drug.expires_at }}
                                            </td>

                                            <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                                <a class="p-2 m-1 cursor-pointer" @click.native="showUpdateModal(drug)">Update</a>
                                                <Link method="delete" class="p-2 m-1" :href="route('pharmacy.stock.destroy', { id: drug.id })">Delete Drug</Link>
                                                <!--                                                <Link class="p-2 m-1" v-if="!drug.code && drug.status === 'unfilled'" :href="route('pharmacy.orders.accept', { id: drug.id })">Accept</Link>-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <Pager :model="drugs" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <modal :show="showUpdateStock" @close="resetForm" class="p-6 my-8">
            <div class="p-5">
                <h2>{{ form.drug?.id ? 'Update' : 'Create' }} {{ form.drug.name }}</h2>

                <div>
                    <form @submit.prevent="updateDrug">
                        <div class="">
                            <div class="py-2">
                                <jet-label for="name" value="Name" />
                                <jet-input  type="text" id="name" placeholder="eg. Paracetamol" v-model="form.drug.name"  class="mt-1 block w-full"/>
                            </div>

                            <div class="py-2">
                                <jet-label for="manufacturer" value="Manufacturer" />
                                <jet-input type="text" id="manufacturer" placeholder="eg. Pfizer" v-model="form.drug.manufacturer"  class="mt-1 block w-full"/>
                            </div>

                            <div class="py-2">
                                <jet-label for="sku" value="SKU" />
                                <jet-input type="text" id="sku" placeholder="eg. SKU288827373" v-model="form.drug.sku"  class="mt-1 block w-full"/>
                            </div>

                            <div class="py-2">
                                <jet-label for="quantity" value="Quantity" />
                                <jet-input type="number"  min="0" step="1" id="quantity" placeholder="eg. 3" v-model="form.drug.quantity"  class="mt-1 block w-full"/>
                            </div>

                            <div class="py-2">
                                <jet-label for="expires_at" value="Expires At" />
                                <jet-input type="date" id="expires_at" placeholder="eg. 2021-10-19" v-model="form.drug.expires_at"  class="mt-1 block w-full"/>
                            </div>

                            <div class="py-2">
                                <Button type="submit">Save</Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </modal>
    </app-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import Button from "@/Jetstream/Button";
    import Pager from "@/Jetstream/Pager";
    import { Link } from "@inertiajs/inertia-vue3";
    import Search from "@/Jetstream/Search";
    import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
    import Modal from "@/Jetstream/Modal";
    import JetLabel from "@/Jetstream/Label";
    import JetInput from "@/Jetstream/Input";

    export default defineComponent({

        data() {
            return {
                confirmingDrugDeletion: false,
                showUpdateStock: false,
                form: this.$inertia.form({
                    drug: {
                        name: null,
                        manufacturer: null,
                        sku: null,
                        expires_at: null,
                        quantity: null,
                    }
                })
            }
        },

        props: ['drugs'],

        components: {
            Button,
            AppLayout,
            Pager,
            Link,
            Search,
            JetConfirmationModal,
            Modal,
            JetLabel,
            JetInput
        },

        methods: {
            showUpdateModal(drug) {
                this.form.drug = drug;
                this.form.drug.expires_at = this.form.drug.expires
                this.showUpdateStock = true
            },

            updateDrug() {

                let useRoute = this.form.drug.id
                    ? route('pharmacy.stock.update', { id: this.form.drug.id})
                    :  route('pharmacy.stock.create')

                this.form.post(useRoute, {
                    onFinish: () => {
                        this.form.reset();
                        this.showUpdateStock = false
                    }
                })
            },

            resetForm() {
                this.form.drug = {
                    name: null,
                    manufacturer: null,
                    sku: null,
                    expires_at: null,
                    quantity: null,
                }

                this.showUpdateStock = false
            }
        }
    })
</script>
