<template>
    <div class="border-t border-gray-300/50 dark:border-gray-700/70 pt-11 md:pt-15 lg:pt-17 xl:pt-20 mt-12 md:mt-15 lg:mt-17 xl:mt-20">

        <!-- Title -->
        <h6>
            Teaser
        </h6>

        <!-- Information -->
        <p class="mb-4 md:mb-7">
            TipSea uses Open Graph to ensure that any tips shared on social media are
            rich and engaging. Use the options below to customize the appearance of the
            link teaser 'card' that will be generated for each tip. Note that the displayed
            content is just an example. The value for 'teaser' in your tip's JSON will be used.
        </p>

        <!-- Screenshot -->
        <v-screenshot :editable="false"
                      :title="session.form.title"
                      :theme="session.form.theme"
                      :content="session.form.teaser"
                      :gradient="session.form.gradient"
                      @theme="session.form.theme = $event; session.form.errors.theme = ''"
                      @gradient="session.form.gradient = $event; session.form.errors.gradient = ''">
        </v-screenshot>

    </div>
</template>

<script>
    import '@caneara/varnish/src/styles/writer.css';
    import ContainerComponent from '@/components/container.vue';
    import ScreenshotComponent from '@/components/screenshot.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-container'  : ContainerComponent,
            'v-screenshot' : ScreenshotComponent,
        },
    }
</script>