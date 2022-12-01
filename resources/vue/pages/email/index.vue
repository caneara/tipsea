<template>
    <v-layout>
        <v-modal :visible="true"
                 :dismiss="false">

            <!-- Header -->
            <h4 class="md:text-center">
                Verify your email address
            </h4>

            <!-- Information -->
            <p class="text-center mb-10">
                We need you to confirm that the email address you
                provided is valid. Check your email for a verification
                link. If you did not receive one, then you can request
                that another be sent.
            </p>

            <!-- Action -->
            <div class="md:flex justify-end">
                <v-button label="Resend"
                          :processing="form.processing"
                          @click="submitForm(form, 'email.verify.send')">
                </v-button>
            </div>

        </v-modal>
    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import ModalComponent from '@caneara/varnish/src/components/modal.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button' : ButtonComponent,
            'v-layout' : Layout,
            'v-modal'  : ModalComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm(),
        }},
    }
</script>