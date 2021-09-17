<template>
    <jet-danger-button @click="openModal()">Delete</jet-danger-button>

    <jet-confirmation-modal :show="deletingStore" @close="closeModal">
        <template #title>
            Delete {{form.url}} Store
        </template>
        <template #content>
            Are you sure you want to delete the {{store.url}} store? Once the store is deleted, all of its resources and data will be permanently deleted.
        </template>
        <template #footer>
            <jet-secondary-button @click="closeModal">
                Cancel
            </jet-secondary-button>

            <jet-danger-button class="ml-2" @click="deleteStore" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Delete Store
            </jet-danger-button>
        </template>
    </jet-confirmation-modal>
</template>

<script>
import {defineComponent} from "vue";

import JetDangerButton from '@/Jetstream/DangerButton.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
import JetActionMessage from '@/Jetstream/ActionMessage.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'

export default defineComponent({
    props: ['store'],
    components: {
        JetDangerButton,
        JetButton,
        JetSecondaryButton,
        JetDialogModal,
        JetConfirmationModal,
        JetActionMessage,
        JetFormSection,
        JetInputError,
        JetLabel,
    },

    data() {
        return {
            deletingStore: false,
            form: this.$inertia.form({
                url: this.store.url,
            })
        }
    },

    methods: {
        openModal() {
            this.deletingStore = true;
        },
        closeModal() {
            this.deletingStore = false
            this.form.reset();
        },
        deleteStore() {
            this.form.delete(route('stores.destroy', this.store), {
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
                onFinish: () => this.form.reset(),
            })
        },
    },
})
</script>
