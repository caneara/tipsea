<template>
    <div class="relative">

        <!-- Control -->
        <div @mouseover="hover = true"
             @mouseout="hover = false"
             class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded relative overflow-hidden transition duration-300">

            <!-- Hidden -->
            <input type="hidden"
                   :value="modelValue"
                   :id="`${name}_file_value`"
                   :name="`${name}_file_value`" />

            <!-- Input -->
            <input :id="name"
                   ref="file"
                   type="file"
                   :name="name"
                   :dusk="name"
				   :accept="types"
                   @change="upload()"
                   :class="automated() ? '' : 'hidden'" />

			<!-- File Name -->
			<input readonly
                   type="text"
				   :value="display"
				   @click="selectNew()"
                   :id="`${name}_file_name`"
                   :name="`${name}_file_name`"
                   :class="hover || focus ? 'pr-50px' : 'pr-3'"
				   class="w-full bg-inherit text-gray-900 dark:text-gray-400 text-ellipsis overflow-hidden rounded appearance-none cursor-pointer pl-3 pt-25px pb-7px" />

            <!-- Label -->
            <v-label :icon="icon"
                     :value="label"
                     :focus="focus"
                     :optional="optional"
                     :filled="! blank(display)">
            </v-label>

            <!-- Clear -->
            <v-clear :focus="focus"
                     :hover="hover"
                     @click="reset()"
                     :filled="! blank(display)">
            </v-clear>

        </div>

        <!-- Progress Bar -->
        <div ref="bar"
             class="w-full h-5px overflow-hidden absolute top-0 dark:left-1px dark:top-1px">

            <!-- Progress -->
            <div ref="progress"
                 class="bg-teal-600/70 dark:bg-emerald-500 h-5px rounded-t rounded-br absolute top-0 left-0 transition-all duration-300">
            </div>

        </div>

        <!-- Error -->
        <v-error :value="fault"></v-error>

    </div>
</template>

<script>
    import Vapor from '../../js/vapor';
    import Utilities from '@caneara/varnish/src/mixins/Utilities';
    import Foundation from '@caneara/varnish/src/mixins/Foundation';
    import ClearComponent from '@caneara/varnish/src/components/clear.vue';
    import ErrorComponent from '@caneara/varnish/src/components/error.vue';
    import LabelComponent from '@caneara/varnish/src/components/label.vue';

    export default
    {
        /**
         * Define the mixins.
         *
         */
        mixins : [
            Utilities,
            Foundation,
        ],

        /**
         * Define the components.
         *
         */
        components : {
            'v-clear' : ClearComponent,
            'v-error' : ErrorComponent,
            'v-label' : LabelComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            display : '',
            size    : 1048576,
            types   : 'image/png, image/jpeg',
        }},

        /**
         * Define the events.
         *
         */
        emits : ['reset', 'uploading', 'uploaded'],

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Update the user interface after completion.
             *
             */
            finish()
            {
                this.$refs.file.value = null;
                this.$refs.bar.classList.add('hidden');
                this.$refs.progress.style.width = '0px';
            },

            /**
             * Clear the state of the component.
             *
             */
            reset(error = '')
            {
                this.display = '';

                this.fault = error;

                this.$emit('reset');

                this.finish();
            },

            /**
             * Remove the selected file and allow for the selection of a new one.
             *
             */
            selectNew()
            {
                this.reset();

                this.$refs.file.click();
            },

            /**
             * Update the current upload progress.
             *
             */
            setProgress(progress)
            {
                this.$refs.progress.style.width = `${Math.round(progress * 100)}%`;
            },

            /**
             * Stream the chosen file to the server.
             *
             */
            upload()
            {
                let file = this.$refs.file.files[0];

				if (file.size > this.size) {
                    return this.reset('The file cannot be larger than 1MB.');
				}

				if (! this.types.replaceAll(' ', '').split(',').includes(file.type.toLowerCase())) {
                    return this.reset('The file must in a JPEG or PNG format.');
				}

                this.fault    = '';
                this.display = file.name;

                this.$emit('uploading', file);

                this.$refs.bar.classList.remove('hidden');

                Vapor.store(file, { progress : progress => this.setProgress(progress) })
                    .then(response => { this.$emit('uploaded', response.uuid); this.finish() });
            },
        }
    }
</script>