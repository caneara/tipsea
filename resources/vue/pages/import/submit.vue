<template>
    <div class="flex flex-col-reverse md:flex-row items-center mt-4"
         :class="feedback || form.errors.payload ? 'justify-between' : 'justify-end'">

        <!-- Feedback -->
        <span v-if="feedback"
              class="font-bold text-[13px] text-emerald-700 dark:text-emerald-500 flex items-center uppercase mt-4 md:mt-0">

            <!-- Icon -->
            <i class="fas fa-spin fa-cog text-17px text-gray-500 mr-3"></i>

            <!-- Text -->
            {{ feedback }}

        </span>

        <!-- Action -->
        <v-button label="Save"
                  @click="sendToServer()"
                  :processing="form.processing">
        </v-button>

    </div>
</template>

<script>
    import Vapor from '../../../js/vapor';
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
         * Define the data model.
         *
         */
        data() { return {
            configuration : {
                pixelRatio   : 2,
                fontEmbedCSS : '',
                type         : 'image/jpeg',
            },

            feedback : '',

            form : this.createForm({
                payload : null,
            }),

            tips : [],
        }},

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Prepare the given tip for submission to the server.
             *
             */
            async format(tip, card)
            {
                tip.shared    = true;
                tip.theme     = window.session.form.theme;
                tip.gradient  = window.session.form.gradient;
                tip.banner_id = window.session.form.banner_id;

                if (card) tip.card = await this.saveImage(tip.title, tip.teaser);

                return tip;
            },

            /**
             * Attempt to read the JSON schema supplied by the user.
             *
             */
            parse()
            {
                window.session.form.errors.json = '';

                try {
                    return this.tips = JSON.parse(window.session.form.json);
                } catch (error) {
                    return ! (window.session.form.errors.json = 'Invalid JSON');
                }
            },

            /**
             * Prepare each of the tips for submission to the server.
             *
             */
            async process(card = false)
            {
                this.form.payload = JSON.stringify(
                    await Promise.all(this.tips.map(tip => this.format(tip, card)))
                );

                window.session.form.teaser = window.session.example;
            },

            /**
             * Generate a screenshot for the given text, upload it, then return its identifier.
             *
             */
            async saveImage(title, text)
            {
                window.session.form.title = title;
                window.session.form.teaser = text;

                this.feedback = 'Generating screenshots...';

                let canvas = await toCanvas(document.querySelector('.screenshot .window'), this.configuration);
                let blob   = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 1));

                this.feedback = 'Uploading screenshots...';

                let id = (await Vapor.store(blob)).uuid;

                this.feedback = '';

                return id;
            },

            /**
             * Batch format the tips and send them to the server.
             *
             */
            async sendToServer()
            {
                if (! this.parse()) return;

                await this.process(false);

                let response = await this.http().post(this.route('imports.verify'), {
                    payload : this.form.payload,
                });

                if (response !== 'valid') {
                    return window.session.form.errors.json = response.message;
                }

                await this.process(true);

                this.submitForm(this.form, 'imports.store');
            },
        }
    }
</script>