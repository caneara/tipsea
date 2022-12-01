<template>
    <v-container type="vertical">

        <!-- Title -->
        <h6>
            Content
        </h6>

        <!-- Information -->
        <p class="mb-4 md:mb-7">
            Now it's time to write the content of your tip. TipSea employs
            the CommonMark flavor of Markdown to style your tip's content,
            so be sure to take full advantage of what it offers. Of course,
            that includes rich syntax highlighting.
        </p>

        <!-- Writer -->
        <v-writer id="content"
                  :height="300"
                  :remaining="true"
                  :maxLength="5000"
                  :lineNumbers="true"
                  v-model="session.form.content"
                  :error="session.form.errors.content"
                  class="public bg-white dark:bg-gray-800"
                  placeholder="Write something amazing...">
        </v-writer>

        <!-- Submit -->
        <v-submit v-if="update"
                  id="save-content">
        </v-submit>

    </v-container>
</template>

<script>
    import SubmitPartial from './submit.vue';
    import ContainerComponent from '@/components/container.vue';
    import WriterComponent from '@caneara/varnish/src/components/writer.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-container' : ContainerComponent,
            'v-submit'    : SubmitPartial,
            'v-writer'    : WriterComponent,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'update' : { type : Boolean, default : false },
        },
    }
</script>