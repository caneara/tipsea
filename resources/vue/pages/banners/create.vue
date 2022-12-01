<template>
    <v-modal :visible="visible"
             @closed="$emit('closed')">

        <!-- Title -->
        <h4 class="text-center">
            Add a new banner
        </h4>

        <!-- Information -->
        <p class="text-center mb-8">
            Images will be resized to 2000 x 250 pixels, so
            be sure your banner has a matching aspect ratio.
            Ideally, upload a 4000 x 500 pixel image to
            avoid any pixelation or rendering issues.
        </p>

        <!-- Name -->
        <v-textbox id="name"
                   label="Name"
                   class="mb-4"
                   :maxLength="30"
                   v-model="form.name"
                   icon="fas fa-signature"
                   :error="form.errors.name">
        </v-textbox>

        <!-- Url -->
        <v-textbox id="url"
                   class="mb-4"
                   :maxLength="100"
                   v-model="form.url"
                   icon="fas fa-link"
                   :error="form.errors.url"
                   label="Url (when clicked)">
        </v-textbox>

        <!-- Graphic -->
        <v-upload id="graphic"
                  class="mb-4"
                  icon="fas fa-image"
                  label="Select an image..."
                  :error="form.errors.graphic"
                  @uploading="form.uploading = true"
                  @reset="form.graphic = ''; form.errors.graphic = ''"
                  @uploaded="form.graphic = $event; form.uploading = false">
        </v-upload>

        <!-- Error Message -->
        <v-error class="mb-6"
                 :value="form.errors.graphic">
        </v-error>

        <!-- Actions -->
        <div class="md:flex justify-end">
            <v-button label="Save"
                      :disabled="form.uploading"
                      :processing="form.processing"
                      @click="submitForm(form, 'banners.store')">
            </v-button>
        </div>

    </v-modal>
</template>

<script>
    import UploadComponent from '@/components/upload.vue';
    import ErrorComponent from '@caneara/varnish/src/components/error.vue';
    import ModalComponent from '@caneara/varnish/src/components/modal.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import TextBoxComponent from '@caneara/varnish/src/components/textbox.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button'  : ButtonComponent,
            'v-error'   : ErrorComponent,
            'v-modal'   : ModalComponent,
            'v-textbox' : TextBoxComponent,
            'v-upload'  : UploadComponent,
        },

        /**
         * Define the events.
         *
         */
        emits : ['closed'],

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                url       : '',
                name      : '',
                graphic   : '',
                uploading : false,
            }),
        }},

        /**
         * Define the public properties.
         *
         */
        props : {
            'visible' : { type : Boolean, default : false },
        },
    }
</script>