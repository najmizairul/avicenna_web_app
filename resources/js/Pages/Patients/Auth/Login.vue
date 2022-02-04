<template>
    <Head title="Log in as Patient" />

    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo class="flex justify-center align-center" />
            <h3 class="font-semibold text-center text-gray-700 text-md p-2">Welcome Patient</h3>
            <h3 class="font-semibold text-center text-gray-700 text-lg p-2">Track My Prescription</h3>
        </template>

        <jet-banner class="mb-4" />

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <jet-label for="ic" value="IC Number" />
                <jet-input id="ic" type="text" class="mt-1 block w-full" v-model="form.ic" required autofocus />
            </div>

            <div class="mt-4">
                <jet-label for="rn" value="RN Number" />
                <jet-input id="rn" type="text" class="mt-1 block w-full" v-model="form.rn" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Track
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
import { defineComponent } from 'vue'
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetBanner from '@/Jetstream/Banner.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetCheckbox from '@/Jetstream/Checkbox.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

export default defineComponent({
    components: {
        Head,
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetBanner,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        Link,
    },

    props: {
        status: String
    },

    data() {
        return {
            form: this.$inertia.form({
                rn: '',
                ic: '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('patients.login'), {
                // onFinish: () => this.form.reset(),
            })
        }
    }
})
</script>
