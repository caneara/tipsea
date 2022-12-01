<template>
    <div class="mb-4"
         dusk="notifications">

        <!-- Tips -->
        <div :key="item.id"
             v-for="item in items.data"
             :dusk="`notification_${item.id}`"
             class="border-t border-gray-200/50 dark:border-gray-800 flex transition duration-300 py-7">

            <!-- Left -->
            <div class="flex flex-col items-center mr-5">

                <!-- User -->
                <v-link :href="route('profile', item.handle)">
                    <v-image :title="item.user"
                             :fallback="userAvatar()"
                             :url="userAvatar(item.avatar)"
                             class="h-30px md:h-40px rounded-full">
                    </v-image>
                </v-link>

            </div>

            <!-- Right -->
            <div class="flex-1">

                <!-- Title -->
                <v-link :href="route('tips.show', item.slug)">
                    <h5 class="text-17px dark:text-gray-400 leading-snug mb-2px">
                        {{ item.title }}
                    </h5>
                </v-link>

                <!-- Meta -->
                <div class="md:flex items-center mt-3 md:mt-0">

                    <!-- User -->
                    <div class="mb-1 md:mb-0">

                        <!-- Icon -->
                        <i class="fas text-10px relative -top-1px mr-2"
                           :class="item.type === 1 ? 'fa-heart text-red-700/60 dark:text-red-400/70' : 'fa-comment text-sky-600/60 dark:text-sky-400/70'">
                        </i>

                        <!-- Name -->
                        <v-link :href="route('profile', item.handle)">
                            <span class="text-13px text-gray-600/70 dark:text-gray-500">
                                {{ item.user }}
                            </span>
                        </v-link>

                    </div>

                    <!-- Date -->
                    <div class="md:ml-4">

                        <!-- Icon -->
                        <i class="fas fa-calendar-alt text-10px text-purple-600/60 dark:text-purple-400/70 relative -top-1px mr-2"></i>

                        <!-- Text -->
                        <span class="text-13px text-gray-600/70 dark:text-gray-500">
                            {{ age(item.created_at) }}
                        </span>

                    </div>

                    <!-- Unread -->
                    <div v-if="! item.read_at"
                         class="unread bg-emerald-500/[.15] dark:bg-emerald-500/20 font-medium text-10px text-emerald-600 dark:text-emerald-500 text-center uppercase inline-block rounded-full relative top-2px px-2 py-1 mt-4 md:ml-4 md:mt-0">

                        <!-- Text -->
                        New

                    </div>

                </div>

                <!-- Message -->
                <v-link v-if="item.message"
                        :href="route('tips.show', item.slug)">

                    <!-- Text -->
                    <p class="text-14px leading-relaxed mt-4 -mb-1">
                        {{ item.message }}
                    </p>

                </v-link>

            </div>

        </div>

        <!-- Paginator -->
        <v-paginator source="notifications"
                     @change="mergeContent($event)">
        </v-paginator>

    </div>
</template>

<script>
    import Pagination from '@/mixins/pagination';
    import LinkComponent from '@/components/link.vue';
    import ImageComponent from '@/components/image.vue';
    import PaginatorComponent from '@/components/paginator.vue';

    export default
    {
        /**
         * Define the mixins.
         *
         */
        mixins : [
            Pagination,
        ],

        /**
         * Define the components.
         *
         */
        components : {
            'v-image'     : ImageComponent,
            'v-link'      : LinkComponent,
            'v-paginator' : PaginatorComponent,
        },

		/**
		 * Execute actions when the component is mounted to the DOM.
		 *
		 */
		mounted()
		{
            // setTimeout(() => this.clearUnreadStatus(), 5000);
		},

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Remove the notification unread badge.
             *
             */
            clearUnreadStatus()
            {
                document.querySelectorAll('.unread').forEach(
                    item => item.classList.add('hidden')
                );
            },

            /**
             * Insert new items into the existing paginator instance.
             *
             */
            mergeContent(event)
            {
                this.mergePaginatorContent(event);

                setTimeout(() => this.clearUnreadStatus(), 5000);
            },
        }
    }
</script>