<template>
    <v-layout>
        <v-modal :visible="true"
                 :dismiss="false">

            <!-- Header -->
            <h4 class="md:text-center">
                Create a new account
            </h4>

            <!-- Information -->
            <p class="text-center mb-8">

                <!-- Text -->
                Welcome aboard. To begin, please fill in the form below,
                and then make sure that you are happy with our

                <!-- Link -->
                <v-link :href="route('legal')">
                    legal terms.
                </v-link>

            </p>

            <!-- Form -->
    	    <form :action="route('register.store')"
                  @submit.prevent="submitForm(form, 'register.store')">

                <!-- Name -->
                <v-textbox id="name"
                           class="mb-4"
                           label="Name"
                           :maxLength="50"
                           autocomplete="name"
                           v-model="form.name"
                           icon="fas fa-signature"
                           :error="form.errors.name">
                </v-textbox>

                <!-- Handle -->
                <v-textbox id="handle"
                           class="mb-4"
                           :maxLength="30"
                           label="Username"
                           icon="fas fa-user"
                           v-model="form.handle"
                           :error="form.errors.handle">
                </v-textbox>

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
                            icon="fas fa-key"
                            label="Confirm password"
                            id="password_confirmation"
                            autocomplete="new-password"
                            v-model="form.password_confirmation"
                            :error="form.errors.password_confirmation"
                            @change="($event) => { form.password = $event; form.errors.password = '' }">
                </v-password>

                <!-- Footer -->
                <div class="md:flex items-center justify-between">

                    <!-- Accept Terms -->
                    <v-switch id="terms"
                              label="Accept Terms"
                              v-model="form.terms"
                              :error="form.errors.terms"
                              class="w-full md:w-[190px] mb-4 md:mb-0">
                    </v-switch>

                    <!-- Create -->
                    <v-button label="Create"
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
    import LinkComponent from '@/components/link.vue';
    import ModalComponent from '@caneara/varnish/src/components/modal.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import SwitchComponent from '@caneara/varnish/src/components/switch.vue';
    import TextBoxComponent from '@caneara/varnish/src/components/textbox.vue';
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
            'v-link'     : LinkComponent,
            'v-modal'    : ModalComponent,
            'v-password' : PasswordComponent,
            'v-switch'   : SwitchComponent,
            'v-textbox'  : TextBoxComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                name                  : '',
                handle                : '',
                email                 : '',
                password              : '',
                password_confirmation : '',
                terms                 : false,
            }),
        }},
    }
</script>