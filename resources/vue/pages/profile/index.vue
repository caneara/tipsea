<template>
    <v-layout>

        <!-- Overview -->
        <div class="flex mb-6 md:mb-12">

            <!-- Left Side -->
            <div class="flex flex-col min-w-30px md:min-w-125px">

                <!-- User -->
                <v-image :fallback="userAvatar()"
                         :url="userAvatar(prop('profile.avatar'))"
                         class="rounded-full max-h-75px md:max-h-125px">
                </v-image>

                <!-- Tips -->
                <div class="hidden md:flex justify-center mt-5">
                    <span class="bg-emerald-600/10 font-medium text-11px text-emerald-700/70 dark:text-emerald-600 uppercase rounded-full px-3 py-1">
                        {{ metric(prop('profile.metrics.tips', 0), 'code tip') }}
                    </span>
                </div>

                <!-- Followers -->
                <div class="hidden md:flex justify-center mt-2">
                    <span class="bg-purple-600/5 dark:bg-purple-600/10 font-medium text-11px text-purple-700/60 dark:text-purple-500 uppercase rounded-full px-3 py-1">
                        {{ metric(prop('profile.metrics.followers', 0), 'follower') }}
                    </span>
                </div>

            </div>

            <!-- Right Side -->
            <div class="flex-1 ml-5 md:ml-10">

                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start mb-6">

                    <!-- Identity -->
                    <div class="flex-1">

                        <!-- Name -->
                        <h2 class="mb-1">
                            {{ prop('profile.name')}}
                        </h2>

                        <!-- Handle -->
                        <span class="text-gray-500/80 dark:text-gray-500">
                            @{{ prop('profile.handle') }}
                        </span>

                    </div>

                    <!-- Action -->
                    <v-button id="follower"
                              mode="outline"
                              @click="toggleFollower()"
                              :color="follower ? 'red' : 'blue'"
                              :label="follower ? 'Unfollow' : 'Follow'"
                              v-if="isAuthenticated() && prop('user.id') !== prop('profile.id')">
                    </v-button>

                </div>

                <!-- Summary -->
                <p class="mb-8">
                    {{ prop('profile.biography') }}
                </p>

                <!-- Links -->
                <div class="flex flex-col md:flex-row justify-between md:items-end mb-3 md:mb-0">

                    <!-- Personal -->
                    <div class="flex mb-6 md:mb-0">

                        <!-- Website -->
                        <a target="_blank"
                           v-if="prop('profile.website')"
                           :href="prop('profile.website')"
                           title="View their personal or professional site"
                           class="flex items-center cursor-pointer group mr-8">

                            <!-- Icon -->
                            <i class="fas fa-globe text-18px text-emerald-600 group-hover:text-emerald-500 transition duration-300"></i>

                            <!-- Text -->
                            <span class="font-medium text-12px text-sky-700 dark:text-sky-600 uppercase ml-3">
                                Website
                            </span>

                        </a>

                        <!-- Donate -->
                        <a target="_blank"
                           v-if="prop('profile.donate')"
                           :href="prop('profile.donate')"
                           title="Thank them for their work by donating"
                           class="flex items-center cursor-pointer group mr-8">

                            <!-- Icon -->
                            <i class="fas fa-sack-dollar text-18px text-yellow-600 group-hover:text-yellow-500 transition duration-300"></i>

                            <!-- Text -->
                            <span class="font-medium text-12px text-sky-700 dark:text-sky-600 uppercase ml-3">
                                Donate
                            </span>

                        </a>

                    </div>

                    <!-- Social Media -->
                    <div class="flex gap-x-8">

                        <!-- Twitter -->
                        <a target="_blank"
                           title="Twitter"
                           v-if="prop('profile.twitter')"
                           :href="prop('profile.twitter')">

                            <!-- Icon -->
                            <i class="fab fa-twitter text-20px text-gray-400 dark:text-gray-600 hover:text-sky-600 dark:hover:text-sky-600 cursor-pointer transition duration-300"></i>

                        </a>

                        <!-- Github -->
                        <a title="Github"
                           target="_blank"
                           v-if="prop('profile.github')"
                           :href="prop('profile.github')">

                            <!-- Icon -->
                            <i class="fab fa-github text-20px text-gray-400 dark:text-gray-600 hover:text-purple-700 dark:hover:text-purple-500 cursor-pointer transition duration-300"></i>

                        </a>

                        <!-- LinkedIn -->
                        <a target="_blank"
                           title="LinkedIn"
                           v-if="prop('profile.linkedin')"
                           :href="prop('profile.linkedin')">

                            <!-- Icon -->
                            <i class="fab fa-linkedin text-20px text-gray-400 dark:text-gray-600 hover:text-blue-700 dark:hover:text-blue-500 cursor-pointer transition duration-300"></i>

                        </a>

                        <!-- YouTube -->
                        <a target="_blank"
                           title="YouTube"
                           v-if="prop('profile.youtube')"
                           :href="prop('profile.youtube')">

                            <!-- Icon -->
                            <i class="fab fa-youtube text-20px text-gray-400 dark:text-gray-600 hover:text-red-600 dark:hover:text-red-500 cursor-pointer transition duration-300"></i>

                        </a>

                        <!-- Facebook -->
                        <a target="_blank"
                           title="Facebook"
                           v-if="prop('profile.facebook')"
                           :href="prop('profile.facebook')">

                            <!-- Icon -->
                            <i class="fab fa-facebook text-20px text-gray-400 dark:text-gray-600 hover:text-blue-700 dark:hover:text-blue-500 cursor-pointer transition duration-300"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <!-- Divider -->
        <div class="bg-gray-200 dark:bg-gray-800 h-1px flex-1 mb-10 md:mb-14"></div>

        <!-- Empty -->
        <v-empty actionLabel="Discover Tips"
                 message="Follow other people instead"
                 :actionCommand="() => redirect('home')"
                 :visible="! prop('tips.data').filter(item => ! item?.deleted ?? false).length">
        </v-empty>

        <!-- Tips -->
        <v-tips :items="prop('tips')"
                :showFollowerMenuOption="false">
        </v-tips>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import TipComponent from '@/components/tips.vue';
    import LinkComponent from '@/components/link.vue';
    import ImageComponent from '@/components/image.vue';
    import EmptyComponent from '@caneara/varnish/src/components/empty.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button' : ButtonComponent,
            'v-empty'  : EmptyComponent,
            'v-image'  : ImageComponent,
            'v-layout' : Layout,
            'v-link'   : LinkComponent,
            'v-tips'   : TipComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            follower : this.prop('follower'),
        }},

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Toggle whether the user is following the profile's owner.
             *
             */
            toggleFollower()
            {
                this.follower
                    ? this.http().delete(route('follower.delete', this.prop('profile.id')))
                    : this.http().post(route('follower.store', this.prop('profile.id')));

                this.follower = ! this.follower;
            },
        }
    }
</script>