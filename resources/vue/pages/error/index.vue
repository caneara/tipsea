<template>
    <main class="bg-gray-50 dark:bg-gray-900 flex justify-center items-center min-h-screen md:p-12 lg:p-14">
        <article class="bg-white/75 dark:bg-gray-800/75 border-y md:border-x border-gray-300 dark:border-gray-700 max-w-600px md:rounded-lg p-10 md:p-14">

            <!-- Logo -->
            <img alt="Logo"
                 class="h-35px mx-auto mb-8"
                 :src="`${$page.props.asset}img/logo.png`">

            <!-- Header -->
            <h4 class="text-center">
                {{ $page.props.code }} - {{ errors[$page.props.code].title }}
            </h4>

            <!-- Description -->
            <p class="text-center mb-4">
                {{ errors[$page.props.code].description }}
            </p>

            <!-- Links -->
            <div class="text-11px text-center uppercase mb-2">

                <!-- Home -->
                <a :href="route('home')">
                    Home
                </a>

                <!-- Separator -->
                <span class="text-10px text-gray-500 relative -top-1px mx-1">
                    &bull;
                </span>

                <!-- Support -->
                <a class="cursor-pointer"
                   @click.stop="contact()">

                    <!-- Text -->
                    Support

                </a>

                <!-- Separator -->
                <span class="text-10px text-gray-500 relative -top-1px mx-1">
                    &bull;
                </span>

                <!-- Legal -->
                <a :href="route('legal')">
                    Legal
                </a>

                <!-- Separator -->
                <span class="text-10px text-gray-500 relative -top-1px mx-1">
                    &bull;
                </span>

                <!-- Dashboard -->
                <a :href="route('dashboard')">
                    Dashboard
                </a>

            </div>

            <!-- Copyright -->
            <div class="text-12px text-gray-500/80 text-center">
                &copy; {{ new Date().getFullYear() }} Caneara. All rights reserved.
            </div>

        </article>
    </main>
</template>

<script>
    import AES from 'crypto-js/aes';
    import enc from 'crypto-js/enc-utf8';

    export default
    {
        /**
         * Define the data model.
         *
         */
        data() { return {
            errors : {
                302 : { title : 'Unexpected Redirect',   description: `The server encounted an unexpected redirect to another page.` },
                401 : { title : 'Unauthorized',          description: `You are not authorized to perform this action.` },
                402 : { title : 'Billing Issue',         description: `You are not able to proceed due to an outstanding payment, or lack of a subscription.` },
                403 : { title : 'Forbidden',             description: `An error was encountered while attempting to access this page.` },
                404 : { title : 'Not Found',             description: `The page you were trying to access could not be found.` },
                405 : { title : 'Not Allowed',           description: `The chosen request method is not supported for the requested resource.` },
                408 : { title : 'Request Timeout',       description: `The server timed out while waiting for a response from an external site. Please try again, or contact us.` },
                419 : { title : 'Page Expired',          description: `The security token for the page has expired. Please refresh the page and try again.` },
                422 : { title : 'Unprocessable Request', description: `The server was unable to process your request. Please try again.` },
                429 : { title : 'Too Many Requests',     description: `Too many requests have been sent. Please wait a few minutes.` },
                500 : { title : 'Server Issue',          description: `The server encountered an unknown error and was unfortunately unable to recover from it. Please try again, or contact us.` },
            },
        }},

        /**
         * Execute actions when the component is instantiated.
         *
         */
        created()
        {
            document.title = 'TipSea - Error';
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

            /**
             * Generate a route using the given parameters.
             *
             */
            route(...parameters)
            {
                return window.route(parameters);
            },
        },
    }
</script>