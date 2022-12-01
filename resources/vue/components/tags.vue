<template>
    <div :dusk="`tags_${tip.id}`"
         class="flex flex-wrap mt-6 mb-3 md:mt-5 md:mb-4">

        <!-- Tags -->
        <div :key="`${tip.id}_${tag}`"
             v-for="tag in ['first_tag', 'second_tag', 'third_tag', 'fourth_tag']">

            <!-- Tag -->
            <v-link v-if="tip[tag]"
                    :href="route('home', { filter : 'tag', search : tip[tag].toLowerCase() })">

                <!-- Item -->
                <span class="inline-block bg-sky-100/70 dark:bg-sky-700/30 font-medium text-10px text-sky-800 dark:text-blue-300/80 uppercase rounded-full px-2 py-1 mr-2 mb-2 md:mb-0">
                    {{ tip[tag] }}
                </span>

            </v-link>

        </div>

    </div>
</template>

<script>
    import LinkComponent from '@/components/link.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-link' : LinkComponent,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'tip' : { type : Object, default : {} },
        },
    }
</script>