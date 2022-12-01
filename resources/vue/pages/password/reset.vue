<template>
    <v-layout>
        <v-modal :visible="true"
                 :dismiss="false">

            <!-- Header -->
            <h4 class="md:text-center">
                Reset your password
            </h4>

            <!-- Information -->
            <p class="md:text-center mb-8">
                Please provide a new password for your account. You may also
                use a randomly-generated password if you prefer.
            </p>

            <!-- Form -->
	        <form :action="route('password.reset.store')"
                  @submit.prevent="submitForm(form, 'password.reset.store')">

                <!-- Password -->
                <v-password class="mb-4"
                            id="password"
                            label="Password"
                            icon="fas fa-key"
                            v-model="form.password"
                            autocomplete="new-password"
                            :error="form.errors.password"
                            @change="($event) => { form.password_confirmation = $event; form.errors.password_confirmation = '' }">
                </v-password>

                <!-- Confirm Password -->
                <v-password class="mb-4"
                            label="Confirm"
                            icon="fas fa-key"
                            id="password_confirmation"
                            autocomplete="new-password"
                            v-model="form.password_confirmation"
                            :error="form.errors.password_confirmation"
                            @change="($event) => { form.password = $event; form.errors.password = '' }">
                </v-password>

                <!-- Action -->
                <div class="md:flex justify-end">
                    <v-button label="Update"
                              :simple="true"
                              :processing="form.processing">
                    </v-button>
                </div>

            </form>

        </v-modal>
    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import ModalComponent from '@caneara/varnish/src/components/modal.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import PasswordComponent from '@caneara/varnish/src/components/password.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button'   : ButtonComponent,
            'v-layout'   : Layout,
            'v-modal'    : ModalComponent,
            'v-password' : PasswordComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                email                 : this.prop('email'),
                token                 : this.prop('token'),
                password              : '',
                password_confirmation : '',
            }),
        }},
    }
</script>