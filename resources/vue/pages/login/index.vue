<template>
    <v-layout>
        <v-modal :visible="true"
                 :dismiss="false">

            <!-- Header -->
            <h4 class="md:text-center">
                Sign into your account
            </h4>

            <!-- Information -->
            <p class="md:text-center mb-8">

                <!-- Text -->
                Access your account by entering your credentials
                below, or if you've forgotten your password, then

                <!-- Link -->
                <v-link :href="route('password.forgot')">
                    click here to reset it.
                </v-link>

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

            <!-- Password -->
            <v-password class="mb-4"
                        id="password"
                        label="Password"
                        icon="fas fa-key"
                        v-model="form.password"
                        :error="form.errors.password"
                        autocomplete="current-password">
            </v-password>

            <!-- Footer -->
            <div class="md:flex items-center justify-between mb-10">

                <!-- Remember -->
                <v-switch id="remember"
                          label="Remember Me"
                          v-model="form.remember"
                          class="w-full md:w-[190px] mb-4 md:mb-0">
                </v-switch>

                <!-- Login -->
                <v-button label="Login"
                          :processing="form.processing"
                          @click="submitForm(form, 'login.store')">
                </v-button>

            </div>

            <!-- Divider -->
            <div class="border-b border-gray-200 dark:border-gray-600 mb-10"></div>

            <!-- Notice -->
            <v-notice format="icon">

                <!-- Text -->
                Don't have an account&hellip;

                <!-- Link -->
                <v-link :href="route('register')">
                    sign up for one
                </v-link>

            </v-notice>

        </v-modal>
    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import LinkComponent from '@/components/link.vue';
    import ModalComponent from '@caneara/varnish/src/components/modal.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import NoticeComponent from '@caneara/varnish/src/components/notice.vue';
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
            'v-notice'   : NoticeComponent,
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
                email    : '',
                password : '',
                remember : false,
            }),
        }},
    }
</script>