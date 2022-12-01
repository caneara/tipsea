<template>
    <v-container type="vertical">

        <!-- Title -->
        <h6>
            Banner
        </h6>

        <!-- Information -->
        <p class="mb-4 md:mb-7">
            Specify if one of your banner images should be shown
            with the tip. Banners are a great way to advertise
            your projects or to guide readers to a related
            resource that may be of interest to them.
        </p>

        <!-- Banner -->
        <v-dropdown id="banner_id"
                    :optional="true"
                    itemValueKey="id"
                    itemTextKey="name"
                    icon="fas fa-image"
                    label="Banner image"
                    :items="prop('banners')"
                    v-model="session.form.banner_id"
                    :error="session.form.errors.banner_id">
        </v-dropdown>

        <!-- Preview -->
        <v-image :fallback="bannerGraphic()"
                 v-if="session.form.banner_id"
                 class="w-full rounded-md mt-4"
                 :url="bannerGraphic(prop('banners').find(item => item.id === session.form.banner_id).graphic)">
        </v-image>

        <!-- Submit -->
        <v-submit v-if="update"
                  id="save-banner">
        </v-submit>

    </v-container>
</template>

<script>
    import SubmitPartial from './submit.vue';
    import ImageComponent from '@/components/image.vue';
    import ContainerComponent from '@/components/container.vue';
    import DropDownComponent from '@caneara/varnish/src/components/dropdown.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-container' : ContainerComponent,
            'v-image'     : ImageComponent,
            'v-dropdown'  : DropDownComponent,
            'v-submit'    : SubmitPartial,
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