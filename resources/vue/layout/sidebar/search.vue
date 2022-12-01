<template>
    <div class="mb-10">

        <!-- Form -->
        <form autocomplete="off"
              @submit.prevent="null"
              @mouseover="hover = true"
              @mouseout="hover = false"
              class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md relative px-3 py-2">

            <!-- Input -->
            <input type="text"
                   maxLength="100"
                   id="search_query"
                   name="search_query"
                   dusk="search_query"
                   @focus="focus = true"
                   v-model="form.search"
                   @focusout="focus = false"
                   placeholder="Search all tips..."
                   @keydown="interceptKeystroke($event)"
                   class="w-full bg-inherit text-15px text-gray-900 dark:text-gray-400 appearance-none relative px-6" />

            <!-- Icon -->
            <i class="fas fa-search text-14px text-gray-400 dark:text-gray-500 absolute top-[10.5px] left-3"></i>

            <!-- Clear -->
            <div class="absolute -top-10px -right-7px">
                <v-clear :focus="focus"
                         :hover="hover"
                         @click="redirect('home')"
                         :filled="! blank(form.search)">
                </v-clear>
            </div>

        </form>

        <!-- Filter -->
        <div v-if="form.filter"
             class="text-11px uppercase flex justify-between items-center mt-3">

            <!-- Text -->
            <div class="text-gray-700 dark:text-gray-500">
                Filter by:
            </div>

            <!-- Options -->
            <div class="block">

                <!-- Tag -->
                <span @click="update('tag')"
                      dusk="search_filter_tag"
                      class="cursor-pointer transition duration-300"
                      :class="form.filter === 'tag' ? 'font-medium text-sky-600 dark:text-sky-500' : 'text-gray-700 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-400'">

                    <!-- Text -->
                    Tag

                </span>

                <!-- Separator -->
                <span class="text-gray-400 dark:text-gray-600 relative -top-1px mx-1">
                    &bull;
                </span>

                <!-- Title -->
                <span @click="update('title')"
                      dusk="search_filter_title"
                      class="cursor-pointer transition duration-300"
                      :class="form.filter === 'title' ? 'font-medium text-sky-600 dark:text-sky-500' : 'text-gray-700 dark:text-gray-500 hover:text-gray-900 dark:hover:text-gray-400'">

                    <!-- Text -->
                    Title

                </span>

            </div>

        </div>

    </div>
</template>

<script>
    import ClearComponent from '@caneara/varnish/src/components/clear.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-clear' : ClearComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            focus : false,
            hover : false,

            form : this.createForm({
                filter : this.queryString('local') ? '' : this.queryString('filter') ?? '',
                local  : '',
                search : this.queryString('local') ? '' : this.queryString('search') ?? '',
            }),
        }},

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Intercept certain keyboard events and handle them accordingly.
             *
             */
            interceptKeystroke(event)
            {
                if (event.key !== 'Enter') return;

                if (this.blank(this.form.search)) return;

                this.form.filter = this.form.filter ? this.form.filter : 'tag';

                this.submitForm(this.form, 'home', 'get');
            },

            /**
             * Toggle the attribute to filter by using the given key.
             *
             */
            update(key)
            {
                if (this.form.filter === key) return;

                this.form.filter = key;

                this.submitForm(this.form, 'home', 'get');
            },
        }
    }
</script>
