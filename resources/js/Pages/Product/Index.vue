<template>
    <app-layout title="Products">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <upload-csv-form v-bind:stores="stores"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetDangerButton from '@/Jetstream/DangerButton.vue'
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
import JetButton from '@/Jetstream/Button.vue'
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetActionMessage from '@/Jetstream/ActionMessage.vue'
import JetFormSection from '@/Jetstream/FormSection.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetInputError from '@/Jetstream/InputError.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetActionSection from '@/Jetstream/ActionSection.vue'
import JetCheckbox from '@/Jetstream/Checkbox.vue'
import UploadCsvForm from "./Partials/UploadCsvForm";

export default defineComponent({
    props: ['stores'],
    components: {
        UploadCsvForm,
        AppLayout,
        JetSecondaryButton,
        JetDangerButton,
        JetButton,
        JetDialogModal,
        JetActionMessage,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetActionSection,
        JetCheckbox
    },

    data() {
        return {
            form: this.$inertia.form({
                _method: 'POST',
                file: '',
                selectedStores: [],
            }),
        }
    },

    methods: {
        submit() {
            this.form.post(route('products.uploadCsv'), {
                errorBag: 'createStore',
                preserveScroll: true,
                onSuccess: () => alert('Form Submitted'),
                onError: () => {
                    alert('FORM ERROR')
                }
            })
        },
        handleChange(e) {
            const { value, checked } = e.target
            if (checked) {
                this.form.selectedStores.push(value)
            } else {
                const index = this.form.selectedStores.findIndex(id => id === value)
                if (index > -1) {
                    this.form.selectedStores.splice(index, 1)
                }
            }
            console.log(this.form.selectedStores)
        }
    }
})
</script>
