<template>
    <v-container>

        <!-- Left -->
        <template #left>

            <!-- Title -->
            <h6>
                Delete your account
            </h6>

            <!-- Information -->
            <p class="mb-4 md:mb-7">
                Remove your public profile, tips,
                banners and engagement history.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-0">
                This action cannot be reversed. Once your
                data is gone, it is gone for good.
            </p>

        </template>

        <!-- Right -->
        <template #right>

            <!-- Password -->
            <v-password class="mb-4"
                        id="password"
                        label="Password"
                        :generate="false"
                        icon="fas fa-key"
                        v-model="form.password"
                        :error="form.errors.password"
                        autocomplete="current-password">
            </v-password>

            <!-- Action -->
            <div class="md:flex justify-end">
                <v-button color="red"
                          label="Delete"
                          :processing="form.processing"
                          @click="submitForm(form, 'account.delete', 'delete')">
                </v-button>
            </div>

        </template>

    </v-container>
</template>

<script>
    import ContainerComponent from '@/components/container.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import PasswordComponent from '@caneara/varnish/src/components/password.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button'    : ButtonComponent,
            'v-container' : ContainerComponent,
            'v-password'  : PasswordComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                password : '',
            }),
        }},
    }
</script>