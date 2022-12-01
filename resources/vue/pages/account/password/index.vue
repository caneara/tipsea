<template>
    <v-container>

        <!-- Left -->
        <template #left>

            <!-- Title -->
            <h6>
                Change your password
            </h6>

            <!-- Information -->
            <p class="mb-4 md:mb-7">
                Replace your current password
                with a secure alternative.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-0">
                You are free to create one or to use
                a randomly-generated password.
            </p>

        </template>

        <!-- Right -->
        <template #right>

            <!-- Form -->
            <form :action="route('password.update')"
                  @submit.prevent="submitForm(form, 'password.update', 'patch')">

                <!-- Current Password -->
                <v-password class="mb-4"
                            :generate="false"
                            id="old_password"
                            icon="fas fa-user"
                            label="Current password"
                            v-model="form.old_password"
                            autocomplete="current-password"
                            :error="form.errors.old_password">
                </v-password>

                <!-- Password -->
                <v-password class="mb-4"
                            icon="fas fa-key"
                            id="new_password"
                            label="New password"
                            autocomplete="new-password"
                            v-model="form.new_password"
                            :error="form.errors.new_password"
                            @change="($event) => { form.new_password_confirmation = $event; form.errors.new_password_confirmation = '' }">
                </v-password>

                <!-- Confirm Password -->
                <v-password class="mb-4"
                            icon="fas fa-key"
                            autocomplete="new-password"
                            label="Confirm new password"
                            id="new_password_confirmation"
                            v-model="form.new_password_confirmation"
                            :error="form.errors.new_password_confirmation"
                            @change="($event) => { form.new_password = $event; form.errors.new_password = '' }">
                </v-password>

                <!-- Action -->
                <div class="md:flex justify-end">
                    <v-button label="Update"
                              :simple="true"
                              :processing="form.processing">
                    </v-button>
                </div>

            </form>

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
                old_password              : '',
                new_password              : '',
                new_password_confirmation : '',
            }),
        }},
    }
</script>