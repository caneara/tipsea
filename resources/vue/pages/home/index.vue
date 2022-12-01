<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Discover
        </h2>

        <!-- Summary -->
        <p class="mb-10">
            Review the latest code tips that have been published on TipSea.
            If you're looking for something in particular, then you can
            perform a title or a tag search to filter the displayed tips.
        </p>

        <!-- Notice -->
        <v-notice format="icon"
                  :dismiss="true"
                  class="-mt-2 mb-10"
                  id="home_explore_features">

            <!-- Text -->
            New around here?

            <!-- Link -->
            <v-link :href="route('features')">
                Explore the features
            </v-link>

            <!-- Text -->
            that TipSea offers&hellip;

        </v-notice>

        <!-- Empty -->
        <v-empty actionLabel="Create Tip"
                 message="No tips match your search query"
                 :actionCommand="() => redirect('tips.create')"
                 :visible="! prop('tips.data').filter(item => ! item?.deleted ?? false).length">
        </v-empty>

        <!-- Tips -->
        <v-tips :items="prop('tips')"></v-tips>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import TipComponent from '@/components/tips.vue';
    import LinkComponent from '@/components/link.vue';
    import EmptyComponent from '@caneara/varnish/src/components/empty.vue';
    import NoticeComponent from '@caneara/varnish/src/components/notice.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-empty'   : EmptyComponent,
            'v-layout'  : Layout,
            'v-link'    : LinkComponent,
            'v-notice'  : NoticeComponent,
            'v-tips'    : TipComponent,
        },
    }
</script>