<template>
    <jet-secondary-button @click="openModal()">Show</jet-secondary-button>

    <jet-dialog-modal :show="showingStore" @close="closeModal">
        <template #title>
            Show Store
        </template>
        <template #content>
            <div>
                <jet-label for="url" value="URL" />
                <jet-input type="text" class="mt-1 block w-full" ref="url" autocomplete="url" v-model="store.url" readonly />
            </div>
            <div>
                <jet-label for="consumer-key" value="Consumer Key" />
                <jet-input type="text" class="mt-1 block w-full" ref="consumer_key" autocomplete="consumer_key"  v-model="store.consumer_key" readonly />
            </div>
            <div>
                <jet-label for="consumer-secret" value="Consumer Secret" />
                <jet-input type="text" class="mt-1 block w-full" ref="consumer_secret" autocomplete="consumer_secret"  v-model="store.consumer_secret" readonly />
            </div>
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
            showingStore: false
        }
    },

    methods: {
        openModal() {
            this.showingStore = true;
        },
        closeModal() {
            this.showingStore = false
        }
    },
})
</script>
