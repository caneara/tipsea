<template>
    <div :class="message ? 'justify-between' : 'justify-end'"
         class="flex flex-col-reverse md:flex-row items-center mt-4">

        <!-- Feedback -->
        <span v-if="message"
              class="font-bold text-[13px] text-emerald-700 dark:text-emerald-500 flex items-center uppercase mt-4 md:mt-0">

            <!-- Icon -->
            <i class="fas fa-spin fa-cog text-17px text-gray-500 mr-3"></i>

            <!-- Text -->
            {{ message }}

        </span>

        <!-- Action -->
        <v-button :id="id"
                  @click="save()"
                  :processing="session.form.processing"
                  :label="method === 'post' ? 'Create' : 'Update'">
        </v-button>

    </div>
</template>

<script>
    import Vapor from '../../../../js/vapor';
    import { toCanvas } from 'html-to-image';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button' : ButtonComponent,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'id'     : { type : String, default : '' },
            'method' : { type : String, default : 'patch' },
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            message : '',
        }},

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Retrieve the configuration settings for the rendering process.
             *
             */
            configuration()
            {
                return {
                    pixelRatio   : 2,
                    fontEmbedCSS : '',
                    type         : 'image/jpeg',
                };
            },

            /**
             * Generate the screenshot, upload it, then submit the form.
             *
             */
            async save()
            {
                window.session.form.processing = true;

                this.message = 'Generating screenshot...';

                let canvas = await toCanvas(document.querySelector('.screenshot .window'), this.configuration());
                let blob   = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 1));

                this.message = 'Uploading screenshot...';

                window.session.form.card = (await Vapor.store(blob)).uuid;

                this.message = '';

                this.setPublishDate();

                this.submitForm(
                    window.session.form,
                    this.method === 'post' ? ['tips.store'] : ['tips.update', this.prop('tip.id')],
                    this.method,
                );
            },

            /**
             * Ensure that the publication date is set for the tip.
             *
             */
            setPublishDate()
            {
                if (window.session.form.publication !== 'release') return;

                let date = new Date();

                date.setTime(date.getTime() + 1000);

                window.session.form.published_at = date.toISOString();
            },
        }
    }
</script>