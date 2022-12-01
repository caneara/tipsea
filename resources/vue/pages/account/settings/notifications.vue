<template>
    <v-container>

        <!-- Left -->
        <template #left>

            <!-- Title -->
            <h6>
                Notifications
            </h6>

            <!-- Text -->
            <p class="mb-4 md:mb-7">
                Specify which notifications you would
                like to be sent to your email address.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-0">
                Regardless of these settings, notifications
                are always available in the notification menu.
            </p>

        </template>

        <!-- Right -->
        <template #right>

            <!-- Comments -->
            <v-switch class="mb-4"
                      label="Comments"
                      id="notifications_email_comments"
                      v-model="form.notifications_email_comments"
                      :error="form.errors.notifications_email_comments">
            </v-switch>

            <!-- Action -->
            <div class="md:flex justify-end">
                <v-button label="Update"
                          :processing="form.processing"
                          @click="submitForm(form, 'settings.update', 'patch', { preserveScroll : true })">
                </v-button>
            </div>

        </template>

    </v-container>
</template>

<script>
    import ContainerComponent from '@/components/container.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import SwitchComponent from '@caneara/varnish/src/components/switch.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button'    : ButtonComponent,
            'v-container' : ContainerComponent,
            'v-switch'    : SwitchComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                notifications_email_comments : this.prop('settings.notifications_email_comments', false),
            }),
        }},
    }
</script>