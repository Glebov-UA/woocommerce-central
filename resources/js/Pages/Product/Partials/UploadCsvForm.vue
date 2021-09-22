<template>
    <jet-form-section @submitted="submit">
        <template #title>
            Upload CSV File
        </template>

        <template #description>
            Upload the data from the CSV file to the chosen stores.
        </template>

        <template #form>
            <div v-for="row in stores" :key="row.id" class="col-span-6 sm:col-span-4">
                <jet-checkbox @change="handleChange" v-bind:value="row.id"/><span class="ml-2 text-sm text-gray-600">{{ row.url }}</span>
            </div>
            <input class="col-span-6 sm:col-span-4" type="file" @input="form.file = $event.target.files[0]" ref="file"/>
            <progress class="col-span-6 sm:col-span-4" v-if="form.progress" :value="form.progress.percentage" max="100">
                {{ form.progress.percentage }}%
            </progress>
        </template>
        <template #actions>
            <jet-button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Submit
            </jet-button>
        </template>
    </jet-form-section>
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

export default defineComponent({
    props: ['stores'],
    components: {
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
