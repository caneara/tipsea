<template>
    <div>

        <!-- Sections -->
        <v-overview :update="action === 'update'"></v-overview>
        <v-banner :update="action === 'update'"></v-banner>
        <v-attribution :update="action === 'update'"></v-attribution>
        <v-content :update="action === 'update'"></v-content>
        <v-teaser :update="action === 'update'"></v-teaser>
        <v-publication :update="action === 'update'"></v-publication>
        <v-sharing :update="action === 'update'"></v-sharing>

    </div>
</template>

<script>
    import BannerPartial from './banner.vue';
    import TeaserPartial from './teaser.vue';
    import ContentPartial from './content.vue';
    import SharingPartial from './sharing.vue';
    import OverviewPartial from './overview.vue';
    import '@caneara/varnish/src/styles/writer.css';
    import AttributionPartial from './attribution.vue';
    import PublicationPartial from './publication.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-attribution' : AttributionPartial,
            'v-banner'      : BannerPartial,
            'v-content'     : ContentPartial,
            'v-overview'    : OverviewPartial,
            'v-publication' : PublicationPartial,
            'v-sharing'     : SharingPartial,
            'v-teaser'      : TeaserPartial,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'action' : { type : String, default : '' },
        },
    }
</script>