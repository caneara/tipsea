<template>
    <div :key="banner.id"
         v-for="banner in prop('banners')"
         class="border border-dashed dark:border-gray-700 flex flex-wrap md:flex-nowrap justify-between rounded p-6 mb-4">

        <!-- Summary -->
        <div class="flex md:flex-col justify-between items-center md:items-start w-full md:w-1/2 mb-6 md:mb-0">

            <!-- Header -->
            <div class="pr-4 md:pr-10 md:mb-4">

                <!-- Name -->
                <h6 class="text-16px mb-6px">
                    {{ banner.name }}
                </h6>

                <!-- Url -->
                <div class="text-14px text-gray-500/80 leading-snug break-all">
                    {{ banner.url }}
                </div>

            </div>

            <!-- Actions -->
            <div class="flex gap-x-3">

                <!-- Edit -->
                <i title="Edit banner"
                   :dusk="`edit-${banner.id}`"
                   @click="$emit('edit', banner)"
                   class="fas fa-edit text-14px text-sky-700 dark:text-sky-400/80 dark:hover:text-sky-300 opacity-50 dark:opacity-100 hover:opacity-100 cursor-pointer transition duration-300">
                </i>

                <!-- Delete -->
                <i title="Delete banner"
                   :dusk="`delete-${banner.id}`"
                   @click="$emit('delete', banner)"
                   class="fas fa-trash-alt text-14px text-red-700 dark:text-red-400/80 dark:hover:text-red-300 opacity-50 dark:opacity-100 hover:opacity-100 cursor-pointer transition duration-300">
                </i>

            </div>

        </div>

        <!-- Graphic -->
        <v-image :fallback="bannerGraphic()"
                 :url="bannerGraphic(banner.graphic)"
                 class="w-full md:w-1/2 object-cover rounded-md">
        </v-image>

    </div>
</template>

<script>
    import ImageComponent from '@/components/image.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-image' : ImageComponent,
        },

        /**
         * Define the events.
         *
         */
        emits : ['delete', 'edit'],
    }
</script>