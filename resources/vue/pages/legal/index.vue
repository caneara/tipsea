<template>
    <v-layout>

        <!-- Legal -->
        <v-writer v-model="text"
                  :readingMode="true">
        </v-writer>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import Writer from '@caneara/varnish/src/components/writer.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-layout' : Layout,
            'v-writer' : Writer,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            text : '',
        }},

        /**
         * Execute actions when the component is instantiated.
         *
         */
        async created()
        {
            let url = this.asset('docs/legal.md');

            this.text = await this.http().get(url, 'text');
        },
    }
</script>