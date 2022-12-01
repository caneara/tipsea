<template>
    <v-container>

        <!-- Left -->
        <template #left>

            <!-- Title -->
            <h6>
                Photo
            </h6>

            <!-- Text -->
            <p class="mb-4 md:mb-7">
                Upload an image that you would like
                to use as your profile picture.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-0">
                Images will be scaled to 300 x 300 pixels,
                so you should ideally use an image that is
                larger than this to avoid pixelation.
            </p>

        </template>

        <!-- Right -->
        <template #right>
            <div class="flex flex-col items-center md:items-end">

                <!-- Preview -->
                <v-image :fallback="userAvatar()"
                         :url="userAvatar(prop('user.avatar'))"
                         class="w-225px h-225px rounded-md mb-4">
                </v-image>

                <!-- Avatar -->
                <v-upload id="avatar"
                          class="w-225px"
                          icon="fas fa-image"
                          label="Select image to use"
                          :error="form.errors.avatar"
                          @uploading="form.uploading = true"
                          @reset="form.avatar = ''; form.errors.avatar = ''"
                          @uploaded="form.avatar = $event; form.uploading = false; submitForm(form, 'avatar.update', 'patch', { preserveScroll : true })">
                </v-upload>

            </div>
        </template>

    </v-container>
</template>

<script>
    import ImageComponent from '@/components/image.vue';
    import UploadComponent from '@/components/upload.vue';
    import ContainerComponent from '@/components/container.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-container' : ContainerComponent,
            'v-image'     : ImageComponent,
            'v-upload'    : UploadComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                avatar    : '',
                uploading : false,
            }),
        }},
    }
</script>