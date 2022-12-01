<template>
    <v-container :border="true"
                 type="vertical">

        <!-- Title -->
        <h6>
            Overview
        </h6>

        <!-- Information -->
        <p>
            Provide a title &amp; summary, then assign up to four
            relevant tags. Remember that tip titles and tags are
            searchable, so it is important to make sure that they
            reflect the search terms that are likely to be used.
        </p>

        <!-- Title -->
        <v-textbox id="title"
                   class="mb-4"
                   label="Title"
                   :maxLength="100"
                   icon="fas fa-heading"
                   v-model="session.form.title"
                   :error="session.form.errors.title">
        </v-textbox>

        <!-- Summary -->
        <v-textbox :lines="4"
                   class="mb-4"
                   id="summary"
                   label="Summary"
                   :maxLength="200"
                   icon="fas fa-signature"
                   v-model="session.form.summary"
                   :error="session.form.errors.summary">
        </v-textbox>

        <!-- Tags -->
        <v-tags id="tags"
                :limit="4"
                class="mb-4"
                v-model="tags"
                :characters="20"
                @change="extract($event)"
                label="Tags (minimum 1, maximum 4)"
                :error="session.form.errors.first_tag">
        </v-tags>

        <!-- Submit -->
        <v-submit v-if="update"
                  id="save-overview">
        </v-submit>

    </v-container>
</template>

<script>
    import SubmitPartial from './submit.vue';
    import ContainerComponent from '@/components/container.vue';
    import TagsComponent from '@caneara/varnish/src/components/tags.vue';
    import TextBoxComponent from '@caneara/varnish/src/components/textbox.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-container' : ContainerComponent,
            'v-tags'      : TagsComponent,
            'v-submit'    : SubmitPartial,
            'v-textbox'   : TextBoxComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            tags : [
                window.session.form.first_tag,
                window.session.form.second_tag,
                window.session.form.third_tag,
                window.session.form.fourth_tag,
            ]
        }},

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
             * Convert the tag array into individual tags.
             *
             */
            extract(event)
            {
                window.session.form.first_tag  = event[0] ?? '';
                window.session.form.second_tag = event[1] ?? '';
                window.session.form.third_tag  = event[2] ?? '';
                window.session.form.fourth_tag = event[3] ?? '';
            },
        }
    }
</script>