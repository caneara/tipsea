<template>
    <v-layout>
        <v-modal :visible="true"
                 :dismiss="false">

            <!-- Header -->
            <h4 class="md:text-center">
                Forgotten your password?
            </h4>

            <!-- Information -->
            <p class="md:text-center mb-8">
                Please provide your email address in order to be sent a
                password reset code. The code will be valid for one hour.
            </p>

            <!-- Email Address -->
            <v-textbox id="email"
                       class="mb-4"
                       :maxLength="255"
                       autocomplete="email"
                       v-model="form.email"
                       label="Email address"
                       icon="fas fa-envelope"
                       :error="form.errors.email">
            </v-textbox>

            <!-- Action -->
            <div class="md:flex justify-end">
                <v-button label="Send"
                          :processing="form.processing"
                          @click="submitForm(form, 'password.forgot.store')">
                </v-button>
            </div>

        </v-modal>
    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import ModalComponent from '@caneara/varnish/src/components/modal.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import TextBoxComponent from '@caneara/varnish/src/components/textbox.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button'  : ButtonComponent,
            'v-layout'  : Layout,
            'v-modal'   : ModalComponent,
            'v-textbox' : TextBoxComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                email : '',
            }),
        }},
    }
</script>