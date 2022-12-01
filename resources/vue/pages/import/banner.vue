<template>
    <v-container :border="true">

        <!-- Left -->
        <template #left>

            <!-- Title -->
            <h6>
                Banner
            </h6>

            <!-- Information -->
            <p class="mb-4 md:mb-7">
                Specify if one of your banner images
                should be shown with each tip.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-0">
                Banner images are a great way to advertise
                your projects or to guide readers to a
                related resource that may be of interest.
            </p>

        </template>

        <!-- Right -->
        <template #right>

            <!-- Banner -->
            <v-dropdown id="banner_id"
                        :optional="true"
                        itemValueKey="id"
                        itemTextKey="name"
            			icon="fas fa-image"
            			label="Banner image"
            			:items="prop('banners')"
                        v-model="session.form.banner_id">
            </v-dropdown>

            <!-- Preview -->
            <v-image :fallback="bannerGraphic()"
                     v-if="session.form.banner_id"
                     class="w-full rounded-md mt-4"
                     :url="bannerGraphic(prop('banners').find(item => item.id === session.form.banner_id).graphic)">
            </v-image>

        </template>

    </v-container>
</template>

<script>
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
        },
    }
</script>