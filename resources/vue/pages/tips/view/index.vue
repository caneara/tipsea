<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Tips
        </h2>

        <!-- Summary -->
        <p class="mb-7">
            Review your existing code tips or create a new one to share
            with the community. You can also schedule the publication
            of your tips on TipSea, as well as other social media platforms.
        </p>

        <!-- Toolbar -->
        <v-toolbar target="tips"
                   :showCreationOptions="true">
        </v-toolbar>

        <!-- Empty -->
        <v-empty class="-mt-2"
                 actionLabel="Create Tip"
                 :visible="! prop('tips.data').length"
                 message="No tips match your search query"
                 :actionCommand="() => redirect('tips.create')">
        </v-empty>

        <!-- Tips -->
        <v-tips :items="prop('tips')"></v-tips>

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