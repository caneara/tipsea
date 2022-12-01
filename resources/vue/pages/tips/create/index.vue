<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Create Tip
        </h2>

        <!-- Summary -->
        <p class="-mb-1 lg:-mb-4 xl:-mb-8">
            Create a new code tip and share it with the community. You can
            also schedule the publication of the tip, as well as have it posted to
            other social media platforms that you have connected TipSea to.
        </p>

        <!-- Form -->
        <v-form action="create"></v-form>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import FormPartial from '../form/index.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-form'   : FormPartial,
            'v-layout' : Layout,
        },

        /**
         * Execute actions when the component is created.
         *
         */
        created()
        {
            window.session.form = this.createForm({
                banner_id    : '',
                title        : '',
                summary      : '',
                teaser       : '',
                theme        : 'dark',
                gradient     : 1,
                card         : '',
                first_tag    : '',
                second_tag   : '',
                third_tag    : '',
                fourth_tag   : '',
                content      : '',
                attribution  : '',
                published_at : '',
                shared       : 0,
                publication  : 'draft',
            });
        },
    }
</script>