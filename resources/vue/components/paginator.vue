<template>
    <div v-if="prop(`${source}.next_page_url`)"
         class="flex items-center mt-14 mb-6 md:mb-0">

        <!-- Left Line -->
        <div class="bg-gray-200 dark:bg-gray-800 h-1px flex-1"></div>

        <!-- Load More -->
        <div @click="load()"
             class="bg-white dark:bg-gray-900 hover:bg-sky-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-700 whitespace-nowrap rounded cursor-pointer transition duration-300 px-5 pt-9px pb-11px">

            <!-- Text -->
            <span class="font-semibold text-12px text-gray-500 dark:text-gray-500 uppercase">
                Load More
            </span>

        </div>

        <!-- Right Line -->
        <div class="bg-gray-200 dark:bg-gray-800 h-1px flex-1"></div>

    </div>
</template>

<script>
    export default
    {
        /**
         * Define the events.
         *
         */
        emits : ['change'],

        /**
         * Define the public properties.
         *
         */
        props : {
            'source' : { type : String, default : '' },
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Retrieve the headers to use for the XHR request.
             *
             */
            headers()
            {
                return {
                    'Content-Type'                : 'application/json',
                    'Accept'                      : 'text/html, application/xhtml+xml',
                    'X-Requested-With'            : 'XMLHttpRequest',
                    'X-Inertia'                   : true,
                    'X-Inertia-Version'           : this.prop('version'),
                    'X-Inertia-Partial-Component' : this.app('page').component,
                    'X-Inertia-Partial-Data'      : this.source,
                };
            },

            /**
             * Retrieve the next batch of items.
             *
             */
            async load()
            {
                let options = { headers : this.headers() };

                let response = await this.http().get(this.prop(`${this.source}.next_page_url`), 'json', options);

                this.$emit('change', response.props[this.source]);
            },
        },
    }
</script>