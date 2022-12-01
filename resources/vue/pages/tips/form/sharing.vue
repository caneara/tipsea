<template>
    <v-container type="vertical"
                 v-if="! prop('published')">

        <!-- Title -->
        <h6>
            Sharing
        </h6>

        <!-- Information -->
        <p class="mb-7">

            <!-- Text -->
            TipSea will automatically post your tip to any
            social media accounts that you have

            <!-- Link -->
            <v-link :href="route('account', { tab : 'settings' })">
                connected up via OAuth.
            </v-link>

            <!-- Text -->
            If you don't want your tip to be automatically
            shared on social media, then flick the switch below.

        </p>

        <!-- Shared -->
        <div class="flex flex-col md:items-start xl:items-end mb-10">
            <v-switch id="shared"
                      class="w-full md:w-300px"
                      v-model="session.form.shared"
                      label="Do Not Share on Social Media">
            </v-switch>
        </div>

        <!-- Submit -->
        <v-submit id="save"
                  :method="update ? 'patch' : 'post'">
        </v-submit>

    </v-container>
</template>

<script>
    import SubmitPartial from './submit.vue';
    import LinkComponent from '@/components/link.vue';
    import ContainerComponent from '@/components/container.vue';
    import SwitchComponent from '@caneara/varnish/src/components/switch.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-container' : ContainerComponent,
            'v-link'      : LinkComponent,
            'v-submit'    : SubmitPartial,
            'v-switch'    : SwitchComponent,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'update' : { type : Boolean, default : false },
        },
    }
</script>