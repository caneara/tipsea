<template>
    <v-modal :visible="visible"
             @closed="$emit('closed')">

        <!-- Title -->
        <h4 class="text-center">
            Edit an existing banner
        </h4>

        <!-- Information -->
        <p class="text-center mb-8">
            Revise the name and url of the banner. If you want to replace
            the existing image, then go ahead and select a new file.
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
                  :optional="true"
                  icon="fas fa-image"
                  label="Select an image..."
                  :error="form.errors.graphic"
                  @uploading="form.uploading = true"
                  @reset="form.graphic = ''; form.errors.graphic = ''"
                  @uploaded="form.graphic = $event; form.uploading = false">
        </v-upload>

        <!-- Actions -->
        <div class="md:flex justify-end">
            <v-button label="Save"
                      :processing="form.processing"
                      @click="submitForm(form, ['banners.update', banner.id], 'patch')">
            </v-button>
        </div>

    </v-modal>
</template>

<script>
    import UploadComponent from '@/components/upload.vue';
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
                name      : this.banner.name,
                url       : this.banner.url,
                graphic   : '',
                uploading : false,
            }),
        }},

        /**
         * Define the public properties.
         *
         */
        props : {
            'banner'  : { type : Object,  default : {} },
            'visible' : { type : Boolean, default : false },
        },
    }
</script>