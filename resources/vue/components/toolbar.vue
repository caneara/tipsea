<template>
    <div class="flex flex-wrap justify-between mb-6 md:mb-8"
         :class="form.search ? 'flex-col-reverse md:flex-row' : ''">

        <!-- Left -->
        <div class="flex flex-wrap">

            <!-- Search -->
            <div dusk="search"
                 @click="search()"
                 class="flex items-center cursor-pointer group py-2 mb-2 md:mb-0">

                <!-- Icon -->
                <i class="fas fa-search text-14px text-gray-400 dark:text-gray-500"></i>

                <!-- Text -->
                <span class="font-medium text-12px text-sky-600 dark:text-sky-500 group-hover:text-rose-700 dark:group-hover:text-rose-400 uppercase transition duration-300 ml-2">
                    Search
                </span>

            </div>

            <!-- Divider -->
            <div class="w-full md:hidden"></div>

            <!-- Query -->
            <div dusk="query"
                 v-if="form.search"
                 @click="update('search')"
                 class="border border-gray-300/60 dark:border-gray-700/60 hover:border-gray-400/60 dark:hover:border-gray-400/60 inline-block rounded-full cursor-pointer transition duration-300 px-4 py-6px md:pt-7px md:pb-5px md:ml-6">

                <!-- Content -->
                <div class="flex items-center">

                    <!-- Icon -->
                    <i class="fas fa-times text-14px text-gray-400 dark:text-gray-600 relative top-[0.5px]"></i>

                    <!-- Text -->
                    <span class="text-11px text-gray-700 dark:text-gray-400 uppercase ml-2">
                        {{ form.search }}
                    </span>

                </div>

            </div>

            <!-- Filter -->
            <div dusk="filter"
                 v-if="form.filter"
                 @click="update('filter')"
                 class="border border-gray-300/60 dark:border-gray-700/60 hover:border-gray-400/60 dark:hover:border-gray-400/60 inline-block rounded-full cursor-pointer transition duration-300 px-4 py-6px md:pt-7px md:pb-5px ml-3">

                <!-- Content -->
                <div class="flex items-center">

                    <!-- Text -->
                    <span class="text-11px text-gray-700 dark:text-gray-400 uppercase mr-2">
                        {{ form.filter }}
                    </span>

                    <!-- Icon -->
                    <i class="fas fa-caret-down text-14px text-gray-400 dark:text-gray-600"></i>

                </div>

            </div>

        </div>

        <!-- Right -->
        <div v-if="showCreationOptions"
             class="flex md:pl-6 -my-1 md:my-0">

            <!-- Import -->
            <div dusk="import"
                 v-if="isAuthenticated()"
                 @click="redirect('imports')"
                 class="flex items-center cursor-pointer group py-2 mr-6 mb-2 md:mb-0">

                <!-- Icon -->
                <i class="fas fa-arrow-up text-14px text-gray-400 dark:text-gray-500"></i>

                <!-- Text -->
                <span class="font-medium text-12px text-sky-600 dark:text-sky-500 group-hover:text-rose-700 dark:group-hover:text-rose-400 uppercase transition duration-300 ml-2">
                    Import
                </span>

            </div>

            <!-- Create -->
            <div dusk="create"
                 v-if="isAuthenticated()"
                 @click="redirect('tips.create')"
                 class="flex items-center cursor-pointer group py-2 mb-2 md:mb-0">

                <!-- Icon -->
                <i class="fas fa-plus text-14px text-gray-400 dark:text-gray-500"></i>

                <!-- Text -->
                <span class="font-medium text-12px text-sky-600 dark:text-sky-500 group-hover:text-rose-700 dark:group-hover:text-rose-400 uppercase transition duration-300 ml-2">
                    Create
                </span>

            </div>

        </div>

    </div>
</template>

<script>
    export default
    {
        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                filter : this.queryString('filter') ?? '',
                local  : true,
                search : this.queryString('search') ?? '',
            }),
        }},

        /**
         * Define the public properties.
         *
         */
        props : {
            'showCreationOptions' : { type : Boolean, default : false },
            'target'              : { type : String,  default : '' },
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Execute a search query for relevant tips.
             *
             */
            async search()
            {
                let result = await this.prompt(
                    'What are you looking for?',
                    "Specify a particular tag to search for e.g. 'Python', or provide part of the tip's title e.g. 'database indexing'.",
                    'Tag or title',
                    '',
                    1,
                    100
                );

                if (this.blank(result)) return;

                this.form.filter = 'tag';
                this.form.search = result;

                this.submitForm(this.form, this.target, 'get');
            },

            /**
             * Update the given key in the form and submit it.
             *
             */
            update(key)
            {
                if (key === 'search') {
                    this.form.search = '';
                    this.form.filter = '';
                }

                if (key === 'filter') {
                    this.form.filter = this.form.filter === 'tag' ? 'title' : 'tag';
                }

                this.submitForm(this.form, this.target, 'get');
            },
        }
    }
</script>