<template>
    <v-layout :container="false">

        <!-- Hero -->
        <div class="bg-sky-50/50 dark:bg-gray-800/50 border-b border-slate-400/30 dark:border-slate-600/30 p-6 py-8 md:p-8 md:pb-10 lg:px-18 lg:py-16 xl:px-18 xl:py-16 -m-6 md:-m-8 lg:-m-14 xl:-mx-18 xl:-my-16">

            <!-- Product -->
            <div class="hidden lg:flex items-center mb-8">

                <!-- Logo -->
                <v-image title="Logo"
                         class="h-30px xl:h-40px"
                         :url="asset('img/logo.png')">
                </v-image>

                <!-- Content -->
                <div class="pt-5px ml-14px">

                    <!-- Name -->
                    <svg viewBox="0 0 305 70"
                         xmlns="http://www.w3.org/2000/svg"
                         class="h-18px xl:h-20px mb-6px text-sky-800 dark:text-gray-300">

                        <!-- Path -->
                        <path d="m.864 15.2c-.224-1.152-.368-2.272-.432-3.36-.064-1.12-.096-2.112-.096-2.97601 0-.896.032-1.90399.096-3.02399s.208-2.256.432-3.408h47.52c.256.8.448 1.744.576 2.832.128 1.056.192 2.08.192 3.072 0 2.496-.656 4.272-1.968 5.328-1.28 1.024-3.296 1.536-6.048 1.536h-2.832c-.704 0-1.712-.016-3.024-.048-1.28-.032-2.288-.048-3.024-.048h-15.408c-.704 0-1.712.016-3.024.048s-2.32.048-3.024.048zm15.984 7.008c0-1.312.016-2.48.048-3.504.032-1.056.048-2.256.048-3.6v-6.336h15.216v6.336c0 1.344.016 2.544.048 3.6.032 1.024.048 2.192.048 3.504v45.792c-1.312.224-2.656.368-4.032.432-1.376.096-2.592.144-3.648.144-1.088 0-2.32-.048-3.696-.144-1.376-.064-2.72-.208-4.032-.432zm39.5295-19.776c1.216-.256 2.56-.416 4.032-.48 1.504-.064 2.72-.096 3.648-.096 1.024 0 2.24.032 3.648.096s2.752.224 4.032.48v65.568c-1.28.224-2.624.368-4.032.432-1.408.096-2.624.144-3.648.144-.928 0-2.144-.048-3.648-.144-1.472-.064-2.816-.208-4.032-.432zm76.6015 22.272c0 4.992-1.024 9.184-3.072 12.576-2.016 3.36-4.8 5.904-8.352 7.632-3.52 1.696-7.552 2.544-12.096 2.544h-14.0642v-11.232c.864-.064 2.192-.128 3.984-.192 1.7922-.064 3.5362-.112 5.2322-.144 1.728-.064 2.912-.096 3.552-.096 2.56 0 4.496-.608 5.808-1.824s2.192-2.672 2.64-4.368c.448-1.728.672-3.36.672-4.896 0-1.504-.224-3.104-.672-4.8-.448-1.728-1.328-3.2-2.64-4.416s-3.248-1.824-5.808-1.824c-.96 0-1.92 0-2.88 0-.928-.032-2.048-.064-3.36-.096v54.432c-1.28.224-2.6402.368-4.0802.432-1.408.096-2.624.144-3.648.144-.928 0-2.144-.048-3.648-.144-1.472-.064-2.816-.208-4.032-.432v-65.088l.672-.67201c4.096-.09599 7.92-.17599 11.472-.24 3.5842-.09599 7.1842-.14399 10.8002-.14399 4.544 0 8.576.832 12.096 2.496 3.552 1.632 6.336 4.144 8.352 7.536 2.048 3.36 3.072 7.632 3.072 12.816zm4.211 37.584c.192-2.176.864-4.304 2.016-6.384 1.152-2.112 2.464-3.808 3.936-5.088 2.688 1.6 5.744 3.056 9.168 4.368 3.424 1.28 6.848 1.92 10.272 1.92 2.784 0 4.784-.672 6-2.016s1.824-2.784 1.824-4.32c0-1.024-.336-2.192-1.008-3.504-.672-1.344-2.352-2.576-5.04-3.696l-11.568-4.8c-3.968-1.664-7.248-3.888-9.84-6.672s-3.888-6.464-3.888-11.04c0-4 1.04-7.504 3.12-10.512s4.88-5.344 8.4-7.008c3.552-1.696 7.504-2.544001 11.856-2.544001 4.544 0 8.8.592001 12.768 1.776001 4 1.184 7.248 2.432 9.744 3.744-.128 2.208-.656 4.336-1.584 6.384-.896 2.016-2.096 3.712-3.6 5.088-2.432-1.152-5.28-2.208-8.544-3.168-3.264-.992-6.192-1.488-8.784-1.488-2.752 0-4.72.624-5.904 1.872-1.152 1.248-1.728 2.512-1.728 3.792 0 1.216.4 2.304 1.2 3.264.832.96 2.304 1.888 4.416 2.784l11.808 4.848c3.104 1.28 5.728 2.88 7.872 4.8 2.176 1.888 3.824 4.032 4.944 6.432 1.12 2.368 1.68 4.944 1.68 7.728 0 3.872-.944 7.376-2.832 10.512-1.856 3.104-4.656 5.568-8.4 7.392-3.744 1.792-8.416 2.688-14.016 2.688-3.872 0-8-.608-12.384-1.824-4.384-1.248-8.352-3.024-11.904-5.328zm59.198-59.18401.672-.67199h14.448v12.624c0 1.344.016 2.544.048 3.6s.048 2.24.048 3.552v26.4c0 1.248-.016 2.384-.048 3.408s-.048 2.192-.048 3.504v12.768h-6.288c-2.752 0-4.912-.8-6.48-2.4s-2.352-3.792-2.352-6.576zm36.336 25.68001c.352.928.592 2 .72 3.216s.192 2.24.192 3.072c0 .864-.064 1.872-.192 3.024s-.368 2.224-.72 3.216h-14.64c-.8 0-1.936 0-3.408 0-1.472-.032-2.992-.064-4.56-.096-1.536-.032-2.816-.064-3.84-.096v-12.144c1.024-.032 2.304-.064 3.84-.096 1.568-.032 3.088-.048 4.56-.048 1.472-.032 2.608-.048 3.408-.048zm5.712-26.352c.256 1.152.4 2.24.432 3.264.064.992.096 1.92.096 2.784s-.112 1.936-.336 3.216c-.192 1.248-.496 2.448-.912 3.6h-19.632c-.8 0-1.936 0-3.408 0-1.472-.032-2.992-.064-4.56-.096-1.536-.032-2.816-.064-3.84-.096v-12.672zm2.4 52.992c0 4.224-.656 7.424-1.968 9.6s-4.016 3.264-8.112 3.264h-24.48v-12.384c2.08-.096 4.064-.192 5.952-.288 1.92-.128 4.048-.192 6.384-.192zm25.415-52.992c1.216-.192 2.672-.33601 4.368-.43201 1.728-.09599 3.232-.14399 4.512-.14399 1.216 0 2.688.048 4.416.14399 1.76.096 3.248.27201 4.464.52801l20.64 64.464c-1.056.608-2.432 1.072-4.128 1.392-1.664.32-3.216.48-4.656.48-2.432 0-4.336-.56-5.712-1.68-1.376-1.152-2.528-3.376-3.456-6.672l-6.864-24.336c-.736-2.688-1.6-5.856-2.592-9.504-.96-3.648-1.856-7.056-2.688-10.224h-.576c-.32 1.76-.752 3.728-1.296 5.904s-1.12 4.352-1.728 6.528c-.576 2.144-1.12 4.096-1.632 5.856l-9.792 33.36c-.96.224-1.984.368-3.072.432-1.088.096-2.24.144-3.456.144-1.152 0-2.464-.096-3.936-.288-1.44-.192-2.592-.448-3.456-.768l-.576-.816zm1.152 51.84c-.448 0-.976 0-1.584 0-.576-.032-1.152-.048-1.728-.048-.576-.032-1.104-.048-1.584-.048h-8.064l4.656-11.952h7.104 1.536c.576-.032 1.152-.048 1.728-.048.576-.032 1.088-.048 1.536-.048h7.008c.48 0 .992.016 1.536.048.576 0 1.152.016 1.728.048h1.584 7.344l3.888 11.952h-8.112c-.448 0-.976.016-1.584.048-.576 0-1.168.016-1.776.048-.576 0-1.088 0-1.536 0z"/>

                    </svg>

                </div>

            </div>

            <!-- Teaser -->
            <h3 class="leading-snug mb-4">
                The experience you've been dreaming of&hellip;
            </h3>

            <!-- Summary -->
            <p class="max-w-1000px leading-relaxed mb-0">
                TipSea fixes the problems of reading, sharing and writing code tips for
                social media. It then super-charges the experience with extras. From
                copying actual code, to smart search tooling, to customizable Open Graph
                &amp; Twitter Cards, it's what code tips and tricks should have always been.
            </p>

        </div>

        <!-- Matrix -->
        <div class="max-w-1000px grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-x-6 gap-y-8 lg:gap-y-12 pt-8 md:pt-9 lg:pt-11 mt-6 md:mt-9 lg:mt-18">

            <!-- Feature -->
            <div v-for="feature in features"
                 class="flex flex-col justify-center lg:items-center">

                <!-- Blob -->
                <div class="hidden lg:flex justify-center items-center w-100px h-100px relative mb-3">

                    <!-- Graphic -->
                    <svg viewBox="0 0 200 200"
                         xmlns="http://www.w3.org/2000/svg"
                         class="text-gray-100/50 dark:text-gray-800/50 w-100px h-100px absolute -left-1">

                        <!-- Path -->
                        <path transform="translate(100 100)"
                              d="M51.9,-51.2C67.4,-36.5,80.2,-18.2,81.5,1.2C82.7,20.7,72.4,41.4,56.9,54.8C41.4,68.2,20.7,74.3,1.9,72.4C-17,70.5,-33.9,60.7,-47.6,47.3C-61.3,33.9,-71.6,17,-71.3,0.3C-71,-16.3,-59.9,-32.6,-46.2,-47.3C-32.6,-62.1,-16.3,-75.3,1,-76.3C18.2,-77.3,36.5,-66,51.9,-51.2Z"/>

                    </svg>

                    <!-- Icon -->
                    <i class="text-26px relative"
                       :class="[feature.icon, feature.color]">
                    </i>

                </div>

                <!-- Name -->
                <h5 class="text-18px dark:text-gray-300 mb-3">

                    <!-- Icon -->
                    <i class="fas fa-star text-13px text-yellow-600/60 dark:text-yellow-500/60 lg:hidden relative -top-2px mr-2"></i>

                    <!-- Text -->
                    {{ feature.name }}

                </h5>

                <!-- Summary -->
                <span class="text-gray-700 dark:text-gray-500 lg:text-center leading-normal lg:px-8 xl:px-4">
                    {{ feature.summary }}
                </span>

            </div>

        </div>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import ImageComponent from '@/components/image.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-image'  : ImageComponent,
            'v-layout' : Layout,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            explanation : false,

            features : [
                {
                    name    : 'Search',
                    icon    : 'fas fa-search',
                    color   : 'text-purple-600',
                    summary : 'Find relevent code tips by filtering on tags and titles.',
                }, {
                    name    : 'Text',
                    icon    : 'fas fa-file-alt',
                    color   : 'text-emerald-600',
                    summary : 'Actual text and code you can copy. No more screenshots!',
                }, {
                    name    : 'Revisions',
                    icon    : 'fas fa-edit',
                    color   : 'text-gray-500',
                    summary : 'Fix typos, move with the times, or adjust based on comments.',
                }, {
                    name    : 'Bookmarks',
                    icon    : 'fas fa-bookmark',
                    color   : 'text-yellow-600',
                    summary : 'Save your favorite code tips for easy reference in the future.',
                }, {
                    name    : 'Follow',
                    icon    : 'fas fa-heart',
                    color   : 'text-red-600',
                    summary : 'Follow your favorite developers and never miss their tips.',
                }, {
                    name    : 'Share',
                    icon    : 'fab fa-twitter',
                    color   : 'text-sky-600',
                    summary : 'Connect social media and have your tips posted automatically.',
                }, {
                    name    : 'Scheduling',
                    icon    : 'fas fa-calendar-alt',
                    color   : 'text-purple-600',
                    summary : 'Create your tips as drafts and release them at specific times.',
                }, {
                    name    : 'Banners',
                    icon    : 'fas fa-image',
                    color   : 'text-sky-600',
                    summary : 'Advertise projects or guide readers to related resources.',
                }, {
                    name    : 'Profile',
                    icon    : 'fas fa-user',
                    color   : 'text-emerald-600',
                    summary : 'Add a bio, website, social media and donation links to your tips.',
                },
            ],
        }},
    }
</script>