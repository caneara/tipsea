<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Banners
        </h2>

        <!-- Summary -->
        <p class="mb-10">
            Manage the banner images that are displayed at the bottom of
            your code tips. You are free to upload and use a maximum of
            {{ prop('limit') }} images. Banners are entirely optional,
            so you do not have to use them if you don't want to.
        </p>

        <!-- Empty -->
        <v-empty :visible="! prop('banners').length"
                 message="You don't have any banners">
        </v-empty>

        <!-- Limit -->
        <v-notice class="-mt-2 mb-6"
                  v-if="prop('banners').length >= prop('limit')">

            <!-- Text -->
            You have reached the limit for banners and cannot add any more.

        </v-notice>

        <!-- Items -->
        <v-items @edit="editItem($event)"
                 @delete="deleteItem($event)"
                 v-if="prop('banners').length">
        </v-items>

        <!-- Actions -->
        <div class="md:flex justify-end mt-4"
             v-if="prop('banners').length < prop('limit')">

            <!-- Create -->
            <v-button label="Create"
                      @click="modals.create = true">
            </v-button>

        </div>

        <!-- Edit -->
        <v-edit :banner="banner"
                v-if="modals.edit"
                :visible="modals.edit"
                @closed="modals.edit = false">
        </v-edit>

        <!-- Create -->
        <v-create v-if="modals.create"
                  :visible="modals.create"
                  @closed="modals.create = false">
        </v-create>

    </v-layout>
</template>

<script>
    import EditPartial from './edit.vue';
    import ItemsPartial from './items.vue';
    import Layout from '@/layout/index.vue';
    import CreatePartial from './create.vue';
    import EmptyComponent from '@caneara/varnish/src/components/empty.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import NoticeComponent from '@caneara/varnish/src/components/notice.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button' : ButtonComponent,
            'v-create' : CreatePartial,
            'v-edit'   : EditPartial,
            'v-empty'  : EmptyComponent,
            'v-items'  : ItemsPartial,
            'v-layout' : Layout,
            'v-notice' : NoticeComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            banner : {},
            modals : {
                create : false,
                edit   : false,
            },
        }},

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Delete the given banner.
             *
             */
            async deleteItem(banner)
            {
                let result = await this.confirm();

                if (result) {
                    this.submitForm(null, ['banners.delete', banner.id], 'delete');
                }
            },

            /**
             * Edit the given banner.
             *
             */
            editItem(banner)
            {
                this.banner = banner;

                this.modals.edit = true;
            },
        }
    }
</script>