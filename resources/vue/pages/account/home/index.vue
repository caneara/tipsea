<template>

    <!-- Text -->
    <p class="mb-10">
        If you're new around here and are feeling a little overwhelmed, then take
        a look at the checklist below. It covers most of TipSea's primary features
        and is a great way to get started&hellip;
    </p>

    <!-- Checklist -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Items -->
        <div :key="item.id"
             @click="item.action()"
             v-for="item in items"
             class="hover:bg-sky-50 dark:hover:bg-sky-700/30 border border-dashed border-gray-400/60 dark:border-gray-700 flex rounded-md cursor-pointer transition duration-300 px-4 pt-6 pb-5">

            <!-- Icon -->
            <i :class="item.icon"
                class="fas text-22px text-center w-[31px] min-w-[31px] max-w-[31px] relative top-2px -ml-1">
            </i>

            <!-- Content -->
            <div class="ml-6">

                <!-- Title -->
                <h5 class="font-medium text-17px text-gray-700 dark:text-gray-300 mb-2">
                    {{ item.title }}
                </h5>

                <!-- Summary -->
                <p class="text-15px text-gray-600/80 mb-0">
                    {{ item.summary }}
                </p>

            </div>

        </div>

    </div>

</template>

<script>
    export default
    {
        /**
         * Define the events.
         *
         */
        emits : ['tab'],

        /**
         * Define the data model.
         *
         */
        data() { return {
            items : [
                {
                    id      : 'profile',
                    icon    : 'fa-user text-emerald-600/80 left-8px',
                    title   : 'Update your profile',
                    summary : 'Add your personal photo, website link, biography and social media accounts.',
                    action  : () => this.$emit('tab', 'profile'),
                }, {
                    id      : 'home',
                    icon    : 'fa-search text-yellow-600/80 left-8px',
                    title   : 'Discover code tips',
                    summary : 'Explore or search for tips that concern your area of interest or study.',
                    action  : () => this.redirect('home'),
                }, {
                    id      : 'banners',
                    icon    : 'fa-image text-sky-600/80 left-6px',
                    title   : 'Manage your banners',
                    summary : 'Upload banner images and attach them to the bottom of your code tips.',
                    action  : () => this.redirect('banners'),
                }, {
                    id      : 'tips',
                    icon    : 'fa-code text-purple-600/80 left-6px',
                    title   : 'Write a code tip',
                    summary : 'Contribute a tip that contains valuable programming or tech advice.',
                    action  : () => this.redirect('tips.create'),
                },
            ],
        }},
    }
</script>