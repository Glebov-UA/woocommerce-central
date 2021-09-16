<template>
    <app-layout title="Stores">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Stores
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <create-store></create-store>
                    <table class="table-fixed w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 w-20">Id</th>
                                <th class="px-4 py-2">URL</th>
                                <th class="px-4 py-2 w-80">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in data">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.url }}</td>
                                <td class="border px-4 py-2">
                                    <show-store v-bind:store="row"/>
                                    <update-store v-bind:store="row"/>
                                    <delete-store v-bind:store="row"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
import CreateStore from "./Partials/CreateStore";
import UpdateStore from "./Partials/UpdateStore";
import ShowStore from "./Partials/ShowStore";
import DeleteStore from "./Partials/DeleteStore";

export default defineComponent({
    props: ['data'],
    components: {
        ShowStore,
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
        CreateStore,
        UpdateStore,
        DeleteStore
    },

})
</script>
