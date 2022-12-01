<template>
    <v-container type="vertical">

        <!-- Title -->
        <h6>
            Attribution
        </h6>

        <!-- Information -->
        <p class="mb-4 md:mb-7">
            If you are reposting a tip, then include a link to where it came
            from e.g. a blog page or social media post. You should also try to
            obtain permission from the original author to ensure that you are
            not in violation of copyright.
        </p>

        <!-- Attribution -->
        <v-textbox class="mb-4"
                   id="attribution"
                   :maxLength="100"
                   :optional="true"
                   icon="fas fa-link"
                   label="Attribution URL"
                   v-model="session.form.attribution"
                   :error="session.form.errors.attribution">
        </v-textbox>

        <!-- Submit -->
        <v-submit v-if="update"
                  id="save-attribution">
        </v-submit>

    </v-container>
</template>

<script>
    import SubmitPartial from './submit.vue';
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
            'v-submit'    : SubmitPartial,
            'v-textbox'   : TextBoxComponent,
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