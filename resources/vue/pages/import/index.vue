<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Import Tips
        </h2>

        <!-- Summary -->
        <p>
            If you have a bunch of pre-existing tips that you would like to
            add to TipSea, then you can do so by uploading a JSON file. You
            can also set a banner and specify the teaser image settings
            that will be used for each tip.
        </p>

        <!-- Notice -->
        <v-notice class="-mb-4 md:-mb-7 lg:-mb-9 xl:-mb-12">
            To prevent an avalanche of posts, TipSea does not publish imported
            tips to any of your connected social media platforms. You are, of
            course, free to publish one or more of them manually by sharing a link.
        </v-notice>

        <!-- Form -->
        <v-banner></v-banner>
        <v-teaser></v-teaser>
        <v-json></v-json>
        <v-submit></v-submit>

    </v-layout>
</template>

<script>
    import JsonPartial from './json.vue';
    import Layout from '@/layout/index.vue';
    import BannerPartial from './banner.vue';
    import SubmitPartial from './submit.vue';
    import TeaserPartial from './teaser.vue';
    import NoticeComponent from '@caneara/varnish/src/components/notice.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-banner' : BannerPartial,
            'v-json'   : JsonPartial,
            'v-layout' : Layout,
            'v-notice' : NoticeComponent,
            'v-submit' : SubmitPartial,
            'v-teaser' : TeaserPartial,
        },

        /**
         * Execute actions when the component is created.
         *
         */
        created()
        {
            window.session.example = 'This is some content for display purposes.\n\n```js\nconst test = "How long is a piece of string";\n```';

            window.session.form = this.createForm({
                banner_id : '',
                teaser    : window.session.example,
                theme     : 'dark',
                gradient  : 1,
                json      : '',
            });
        },
    }
</script>