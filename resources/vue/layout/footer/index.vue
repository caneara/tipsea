<template>
    <footer class="bg-slate-100/50 dark:bg-gray-1000 border-t border-slate-400/30 dark:border-slate-600/30">
        <div class="max-w-[1400px] flex flex-col md:flex-row md:justify-between md:items-center px-6 py-5 md:px-8 mx-0">

            <!-- Copyright -->
            <div class="text-gray-600/[.55] dark:text-gray-500/80 text-12px md:text-13px text-center md:text-left">
                &copy; {{ new Date().getFullYear() }} Caneara. All rights reserved.
            </div>

            <!-- Links -->
            <div class="text-11px text-center md:text-left uppercase mt-5px md:mt-0">

                <!-- Legal -->
                <v-link :href="route('legal')"
                        class="text-gray-600/[.55] dark:text-gray-500/80 hover:text-sky-600">

                    <!-- Text -->
                    Legal

                </v-link>

                <!-- Divider -->
                <span class="text-13px text-gray-400/60 dark:text-gray-700 mx-1 md:mx-6px">
                    &bull;
                </span>

                <!-- Support -->
                <a @click.stop="contact()"
                   class="text-gray-600/[.55] dark:text-gray-500/80 hover:text-sky-600 cursor-pointer">

                    <!-- Text -->
                    Support

                </a>

                <!-- Divider -->
                <span v-if="isAuthenticated()"
                      class="text-13px text-gray-400/60 dark:text-gray-700 mx-1 md:mx-6px">

                    <!-- Text -->
                    &bull;

                </span>

                <!-- Logout -->
                <v-link :href="route('logout')"
                        v-if="isAuthenticated()"
                        class="text-gray-600/[.55] dark:text-gray-500/80 hover:text-sky-600">

                    <!-- Text -->
                    Sign Out

                </v-link>

            </div>

        </div>
    </footer>
</template>

<script>
    import AES from 'crypto-js/aes';
    import enc from 'crypto-js/enc-utf8';
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
		 * Define the supporting methods.
		 *
		 */
		methods:
        {
			/**
			 * Allow the user to contact the support team.
			 *
			 */
			contact()
			{
                let target = 'U2FsdGVkX1+ZUnFTs2lxCCzR83cDRgys56e0LtJVsk9+YQ7+MBkn86j4yHO77GuQ';

                window.location.href = AES.decrypt(target, 'caneara').toString(enc);
			},
        },
    }
</script>