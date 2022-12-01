<template>
    <div class="border-t border-gray-300/50 dark:border-gray-700/70 pt-11 md:pt-15 lg:pt-17 xl:pt-20 mt-12 md:mt-15 lg:mt-17 xl:mt-20">

        <!-- Title -->
        <h6>
            Teaser
        </h6>

        <!-- Information -->
        <p class="mb-4 md:mb-7">

            <!-- Text -->
            TipSea uses Open Graph to ensure that any tips shared on social media are
            rich and engaging. Use the form below to customize the content and
            appearance of the link's teaser 'card'. Not sure what to write?

            <!-- Hint -->
            <span @click="hint()"
                  class="text-sky-600 dark:text-sky-500 hover:text-rose-700 dark:hover:text-rose-400 cursor-pointer transition duration-300">

                <!-- Text -->
                See an example.

            </span>

        </p>

        <!-- Screenshot -->
        <v-screenshot :editable="true"
                      :title="session.form.title"
                      :theme="session.form.theme"
                      :content="session.form.teaser"
                      :gradient="session.form.gradient"
                      :error="session.form.errors.teaser"
                      @theme="session.form.theme = $event; session.form.errors.theme = ''"
                      @content="session.form.teaser = $event; session.form.errors.teaser = ''"
                      @gradient="session.form.gradient = $event; session.form.errors.gradient = ''">
        </v-screenshot>

        <!-- Submit -->
        <v-submit v-if="update"
                  id="save-teaser">
        </v-submit>

    </div>
</template>

<script>
    import SubmitPartial from './submit.vue';
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
            'v-submit'     : SubmitPartial,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'update' : { type : Boolean, default : false },
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Insert an example of the social media image teaser.
             *
             */
            async hint()
            {
                let url = this.asset('docs/example.md');

                window.session.form.teaser = await this.http().get(url, 'text');
            },
        }
    }
</script>