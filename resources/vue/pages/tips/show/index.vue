<template>
    <v-layout>

        <!-- Profile -->
        <v-profile></v-profile>

        <!-- Content -->
        <v-content></v-content>

        <!-- Banner -->
        <v-image :fallback="bannerGraphic()"
                 v-if="prop('tip.banner.graphic')"
                 :url="bannerGraphic(prop('tip.banner.graphic'))"
                 @click="redirect(prop('tip.banner.url'), {}, true, true)"
                 class="w-full rounded-md md:rounded-lg object-cover cursor-pointer mb-12">
        </v-image>

        <!-- Divider -->
        <div v-if="prop('tip.banner.graphic') && prop('related.data').length"
             class="border-t border-slate-400/30 dark:border-slate-600/30 pt-12 mt-12">
        </div>

        <!-- Tips -->
        <v-tips :items="prop('related')"
                v-if="prop('related.data').length">
        </v-tips>

        <!-- Comments -->
        <v-comments :items="prop('comments')"></v-comments>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import ContentPartial from './content.vue';
    import ProfilePartial from './profile.vue';
    import CommentPartial from './comments.vue';
    import TipComponent from '@/components/tips.vue';
    import ImageComponent from '@/components/image.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-comments' : CommentPartial,
            'v-content'  : ContentPartial,
            'v-image'    : ImageComponent,
            'v-layout'   : Layout,
            'v-profile'  : ProfilePartial,
            'v-tips'     : TipComponent,
        },

        /**
         * Execute actions when the component is created.
         *
         */
        created()
        {
            setTimeout(() => window.history.replaceState(null, null, window.location.pathname), 500);
        },
    }
</script>