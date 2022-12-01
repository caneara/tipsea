<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Notifications
        </h2>

        <!-- Summary -->
        <p class="mb-10">

            <!-- Text -->
            Review the latest feedback (likes and comments) left in response
            to your code tips. Start receiving notifications by contributing
            to the discussion around existing tips, or by

            <!-- Link -->
            <v-link :href="route('tips')">
                writing your own tips.
            </v-link>

        </p>

        <!-- Empty -->
        <v-empty class="-mt-2"
                 title="You're all caught up"
                 message="No notifications are waiting for you"
                 :visible="! prop('notifications.data').length">
        </v-empty>

        <!-- Notifications -->
        <v-items :items="prop('notifications')"></v-items>

    </v-layout>
</template>

<script>
    import ItemPartial from './items.vue';
    import Layout from '@/layout/index.vue';
    import LinkComponent from '@/components/link.vue';
    import EmptyComponent from '@caneara/varnish/src/components/empty.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-empty'  : EmptyComponent,
            'v-items'  : ItemPartial,
            'v-layout' : Layout,
            'v-link'   : LinkComponent,
        },
    }
</script>