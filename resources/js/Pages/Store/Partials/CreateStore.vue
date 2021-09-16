<template>
    <jet-button @click="openModal()">Add New Store</jet-button>

    <jet-dialog-modal :show="addingNewStore" @close="closeModal">
        <template #title>
            Add New Store
        </template>
        <template #content>
            <form @submit.prevent="createStore">
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
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetActionMessage from '@/Jetstream/ActionMessage.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'

export default defineComponent({
    components: {
        JetDangerButton,
        JetButton,
        JetDialogModal,
        JetActionMessage,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton
    },

    data() {
        return {
            addingNewStore: false,
            form: this.$inertia.form({
                url: '',
                consumer_key: '',
                consumer_secret: '',
            }),
        }
    },

    methods: {
        openModal() {
            this.addingNewStore = true;
        },
        closeModal() {
            this.addingNewStore = false

            this.form.reset()
        },
        createStore() {
            this.form.post(route('stores.store'), {
                errorBag: 'createStore',
                preserveScroll: true,
                onSuccess: () => alert('FORM SubmiTTED'),
                onError: () => {
                    alert('FORM ERROR')
                }
            })
        },
    },
})
</script>
