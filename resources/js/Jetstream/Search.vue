<template>
    <div>
        <div class="flex flex-col justify-between">
            <form class="flex items-center flex-shrink-0" @submit.prevent="search">
                <div>
                    <jet-input id="search" type="text" class="mt-1 block w-full" v-model="form.search" required autofocus />
                </div>

                <div class="flex justify-between items-center">
                    <Link :href="route" v-if="form.search.length > 0" class="text-gray-400 ml-2 mt-1 block w-full4" >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </Link>

                    <jet-button class="ml-4" type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Search
                    </jet-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";

export default {
    props: ['route'],

    data() {
        return {
            form: this.$inertia.form({
               search:  (new URLSearchParams(window.location.search)).get('search') ?? ''
            })
        }
    },

    components: {
        JetInput,
        Link,
        JetButton,
    },

    methods: {
        search() {
            this.form.get(this.route, {
                onFinish: () => this.form.reset()
            })
        }
    }
}
</script>
