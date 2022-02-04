<template>
    <div class="flex justify-between items-center" >

        <Button v-if="model.current_page > 1" @click="prev">Prev</Button>

        <div class="py-3">
            <jet-label for="perPage" value="Show per page:" />
            <select @change="updatePerPage" id="perPage" aria-placeholder="10"  class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="perPage">
                <option :value="option" v-for="(option, key) in perPageOptions" :key="key">
                    {{ option }}
                </option>
            </select>
        </div>

        <Button v-if="model.current_page !== model.last_page" @click="next">Next</Button>


    </div>
</template>

<script>
import { defineComponent } from "vue";
import Button from "@/Jetstream/Button";
import { Link } from "@inertiajs/inertia-vue3";
import JetLabel from "@/Jetstream/Label";

export default defineComponent({
    props: ['model'],

    data() {
        return {
            perPage:  (new URLSearchParams(window.location.search)).get('perPage') ?? 10,

            perPageOptions: [5, 10, 15, 20, 50]
        }
    },

    components: {
        Button,
        Link,
        JetLabel,
    },

    methods: {
        routeToPage(page) {
            this.$inertia.get(route(route().current(), {
                _query: {
                    ...route().params,
                    page,
                    perPage: this.perPage ?? 10,
                }
            }))
        },

        updatePerPage() {
            this.$inertia.get(route(route().current(), {
                _query: {
                    ...route().params,
                    page: 1,
                    perPage: this.perPage
                }
            }))
        },

        prev() {
            this.routeToPage(this.model.current_page -1)
        },

        next() {
            this.routeToPage(this.model.current_page +1)
        }
    }
})
</script>
