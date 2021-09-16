<template>
    <jet-button @click="openModal()">Edit</jet-button>

    <jet-dialog-modal :show="updatingStore" @close="closeModal">
        <template #title>
            Edit Store
        </template>
        <template #content>
            <form @submit.prevent="updateStore">
                <div>
                    <jet-label for="url" value="URL" />
                    <jet-input type="text" class="mt-1 block w-full" v-model="form.url" ref="url" autocomplete="url" />
                </div>
                <div>
                    <jet-label for="consumer-key" value="Consumer Key" />
                    <jet-input type="text" class="mt-1 block w-full" v-model="form.consumer_key" ref="consumer_key" autocomplete="consumer_key" />
                </div>
                <div>
                    <jet-label for="consumer-secret" value="Consumer Secret" />
                    <jet-input type="text" class="mt-1 block w-full" v-model="form.consumer_secret" ref="consumer_secret" autocomplete="consumer_secret" />
                </div>
                <jet-button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Submit
                </jet-button>
            </form>
            <li v-for="error in form.errors">
                {{ error }}
            </li>
        </template>
        <template #footer>
            <jet-secondary-button @click="closeModal">
                Cancel
            </jet-secondary-button>
        </template>
    </jet-dialog-modal>
</template>

<script>
import {defineComponent} from "vue";

import JetDangerButton from '@/Jetstream/DangerButton.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetActionMessage from '@/Jetstream/ActionMessage.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'

export default defineComponent({
    props: ['store'],
    components: {
        JetDangerButton,
        JetButton,
        JetSecondaryButton,
        JetDialogModal,
        JetActionMessage,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
    },

    data() {
        return {
            updatingStore: false,
            form: this.$inertia.form({
                url: this.store.url,
                consumer_key: this.store.consumer_key,
                consumer_secret: this.store.consumer_secret,
            }),
        }
    },

    methods: {
        openModal() {
            this.updatingStore = true;
        },
        closeModal() {
            this.updatingStore = false

            this.form.reset()
        },
        updateStore() {
            this.form.put(route('stores.update', this.store), {
                errorBag: 'updateStore',
                preserveScroll: true,
                onSuccess: () => alert('Form Submitted'),
                onError: () => {
                    alert('FORM ERROR')
                }
            })
        },
    },
})
</script>
