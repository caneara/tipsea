<template>
    <div class="border-t border-gray-300/50 dark:border-gray-700/70 pt-11 md:pt-15 lg:pt-17 xl:pt-20 mt-12 md:mt-15 lg:mt-17 xl:mt-20">

        <!-- Title -->
        <h6>
            JSON
        </h6>

        <!-- Information -->
        <p class="mb-4 md:mb-7">
            In order to upload your tips, you will need to supply them as a JSON array.
            Each element will need to conform to the following structure and rules.
            An example schema is included below the table to help guide you.
        </p>

        <!-- Schema -->
        <v-schema></v-schema>

        <!-- JSON -->
        <v-textbox id="json"
                   :lines="20"
                   icon="fas fa-code"
                   label="JSON schema"
                   class="mb-4 font-mono"
                   v-model="session.form.json"
                   :error="session.form.errors.json">
        </v-textbox>

    </div>
</template>

<script>
    import SchemaPartial from './schema.vue';
    import ContainerComponent from '@/components/container.vue';
    import TextBoxComponent from '@caneara/varnish/src/components/textbox.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-container' : ContainerComponent,
            'v-schema'    : SchemaPartial,
            'v-textbox'   : TextBoxComponent,
        },
    }
</script>