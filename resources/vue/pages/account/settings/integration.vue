<template>
    <v-container>

        <!-- Left -->
        <template #left>

            <!-- Title -->
            <h6>
                Integration
            </h6>

            <!-- Text -->
            <p class="mb-4 md:mb-7">
                Link your Twitter account via
                OAuth to enable automatic posting.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-4">
                TipSea will never use your Twitter
                account for anything besides posting tips.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-0">
                The social media posting service runs
                every five minutes. So you may need to wait
                a short time.
            </p>

        </template>

        <!-- Right -->
        <template #right>

            <!-- Actions -->
            <div class="md:flex justify-end mb-4">
                <v-button @click="toggle()"
                          icon="fab fa-twitter"
                          :color="exists() ? 'red' : 'blue'"
                          :mode="exists() ? 'outline' : 'opaque'"
                          :label="`${exists() ? 'Disconnect' : 'Connect'}`">
                </v-button>
            </div>

            <!-- Notice -->
            <div class="flex justify-end">
                <v-notice class="max-w-400px">
                    When disconnecting, you will need to manually revoke
                    access on Twitter (in connected apps).
                </v-notice>
            </div>

        </template>

    </v-container>
</template>

<script>
    import ContainerComponent from '@/components/container.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import NoticeComponent from '@caneara/varnish/src/components/notice.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button'    : ButtonComponent,
            'v-container' : ContainerComponent,
            'v-notice'    : NoticeComponent,
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Determine whether the user is connected to Twitter.
             *
             */
            exists()
            {
                return this.prop('integration');
            },

            /**
             * Connect or disconnect from Twitter.
             *
             */
            toggle()
            {
                this.prop('integration')
                    ? this.submitForm(null, 'integration.delete', 'delete')
                    : this.redirect(this.route('integration.create'), {}, true);
            },
        },
    }
</script>