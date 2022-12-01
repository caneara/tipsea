<template>
    <div class="bg-sky-50/50 dark:bg-gray-800/50 border border-gray-200/50 dark:border-gray-700/50 flex flex-col md:flex-row md:justify-between rounded-md p-5 mb-10">

        <!-- Overview -->
        <div class="flex items-center">

            <!-- Avatar -->
            <v-link :href="route('profile', prop('tip.user.handle'))">
                <v-image :fallback="userAvatar()"
                         :url="userAvatar(prop('tip.user.avatar'))"
                         class="w-45px md:w-50px rounded-full cursor-pointer">
                </v-image>
            </v-link>

            <!-- Profile -->
            <div class="ml-4">

                <!-- Name -->
                <v-link :href="route('profile', prop('tip.user.handle'))">
                    <h6 class="font-medium text-gray-800 dark:text-gray-300 mb-7px md:mb-6px">
                        {{ prop('tip.user.name') }}
                    </h6>
                </v-link>

                <!-- Donate -->
                <a target="_blank"
                   v-if="prop('tip.user.donate')"
                   :href="prop('tip.user.donate')"
                   class="flex items-center cursor-pointer"
                   title="Thank them for their work by donating">

                    <!-- Icon -->
                    <i class="fas fa-sack-dollar text-14px text-yellow-600"></i>

                    <!-- Text -->
                    <span class="font-medium text-12px text-sky-700 dark:text-sky-600 uppercase ml-2">
                        Donate To Author
                    </span>

                </a>

                <!-- Handle -->
                <div v-if="! prop('tip.user.donate')"
                     class="text-15px text-gray-500/80 dark:text-gray-500 -mt-2px">

                    <!-- Text -->
                    @{{ prop('tip.user.handle') }}

                </div>

            </div>

        </div>

        <!-- Social Media -->
        <div class="flex md:justify-end items-center flex-1 gap-x-5 md:gap-x-6 ml-[61px] mt-2 md:mt-0">

            <!-- Twitter -->
            <a target="_blank"
               title="Website"
               v-if="prop('tip.user.website')"
               :href="prop('tip.user.website')">

                <!-- Icon -->
                <i class="fas fa-globe text-14px md:text-20px text-gray-400 dark:text-gray-600 hover:text-emerald-600 dark:hover:text-emerald-600 cursor-pointer transition duration-300"></i>

            </a>

            <!-- Twitter -->
            <a target="_blank"
               title="Twitter"
               v-if="prop('tip.user.twitter')"
               :href="prop('tip.user.twitter')">

                <!-- Icon -->
                <i class="fab fa-twitter text-14px md:text-20px text-gray-400 dark:text-gray-600 hover:text-sky-600 dark:hover:text-sky-600 cursor-pointer transition duration-300"></i>

            </a>

            <!-- Github -->
            <a title="Github"
               target="_blank"
               v-if="prop('tip.user.github')"
               :href="prop('tip.user.github')">

                <!-- Icon -->
                <i class="fab fa-github text-14px md:text-20px text-gray-400 dark:text-gray-600 hover:text-purple-700 dark:hover:text-purple-500 cursor-pointer transition duration-300"></i>

            </a>

            <!-- LinkedIn -->
            <a target="_blank"
               title="LinkedIn"
               v-if="prop('tip.user.linkedin')"
               :href="prop('tip.user.linkedin')">

                <!-- Icon -->
                <i class="fab fa-linkedin text-14px md:text-20px text-gray-400 dark:text-gray-600 hover:text-blue-700 dark:hover:text-blue-500 cursor-pointer transition duration-300"></i>

            </a>

            <!-- YouTube -->
            <a target="_blank"
               title="YouTube"
               v-if="prop('tip.user.youtube')"
               :href="prop('tip.user.youtube')">

                <!-- Icon -->
                <i class="fab fa-youtube text-14px md:text-20px text-gray-400 dark:text-gray-600 hover:text-red-600 dark:hover:text-red-500 cursor-pointer transition duration-300"></i>

            </a>

            <!-- Facebook -->
            <a target="_blank"
               title="Facebook"
               v-if="prop('tip.user.facebook')"
               :href="prop('tip.user.facebook')">

                <!-- Icon -->
                <i class="fab fa-facebook text-14px md:text-20px text-gray-400 dark:text-gray-600 hover:text-blue-700 dark:hover:text-blue-500 cursor-pointer transition duration-300"></i>

            </a>

        </div>

    </div>
</template>

<script>
    import LinkComponent from '@/components/link.vue';
    import ImageComponent from '@/components/image.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-image' : ImageComponent,
            'v-link'  : LinkComponent,
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Calculate the average amount of time to read the tip.
             *
             */
            estimatedReadingTime()
            {
                return Math.ceil(this.prop('tip.content').trim().split(/\s+/).length / 255);
            },
        }
    }
</script>