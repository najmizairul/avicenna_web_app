<template>
    <div class="py-3">
        <jet-label :for="by" :value="'Filter by ' + by + ':'" />
        <select @change="filter" :id="by" aria-placeholder="all"  class="capitalize border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="filterBy">
            <option :value="option" v-for="(option, key) in filterOptions" :key="key">
                {{ option }}
            </option>
        </select>
    </div>
</template>

<script>
import { defineComponent } from "vue";
import Button from "@/Jetstream/Button";
import { Link } from "@inertiajs/inertia-vue3";
import JetLabel from "@/Jetstream/Label";

export default defineComponent({
    props: ['default', 'options', 'by'],

    data() {
        return {
            filterBy: (new URLSearchParams(window.location.search)).get(this.by) ?? this.default,
            filterOptions: this.options ?? [],
        }
    },

    methods: {
        filter() {
            this.$inertia.get(route(route().current(), {
                _query: {
                    ...route().params,
                    page: 1,
                    [this.by]: this.filterBy,
                }
            }))
        }
    },

    components: {
        Button,
        Link,
        JetLabel,
    },

})
</script>
