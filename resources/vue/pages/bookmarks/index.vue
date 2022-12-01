<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Bookmarks
        </h2>

        <!-- Summary -->
        <p class="mb-7">
            Review each of the code tips that you have favorited
            for future consideration. If you find them valuable,
            then let the author of the tip know by contributing
            feedback or donating to support their work.
        </p>

        <!-- Toolbar -->
        <v-toolbar target="bookmarks"></v-toolbar>

        <!-- Empty -->
        <v-empty class="-mt-2"
                 actionLabel="Discover Tips"
                 :actionCommand="() => redirect('home')"
                 message="Follow people find content of value"
                 :visible="! prop('tips.data').filter(item => ! item?.deleted ?? false).length">
        </v-empty>

        <!-- Tips -->
        <v-tips :items="prop('tips')"
                :deleteOnRemoveBookmark="true">
        </v-tips>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import TipComponent from '@/components/tips.vue';
    import ToolbarComponent from '@/components/toolbar.vue';
    import EmptyComponent from '@caneara/varnish/src/components/empty.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-empty'   : EmptyComponent,
            'v-layout'  : Layout,
            'v-tips'    : TipComponent,
            'v-toolbar' : ToolbarComponent,
        },
    }
</script>