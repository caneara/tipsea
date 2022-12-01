<template>
    <main class="flex flex-col justify-between min-h-screen">

        <!-- Header -->
        <v-header></v-header>

        <!-- Content -->
        <section class="bg-white dark:bg-gray-900 flex flex-1">

            <!-- Sidebar -->
            <v-sidebar></v-sidebar>

            <!-- Content -->
            <article :class="container ? 'max-w-1000px' : ''"
                     class="flex-1 p-6 md:p-8 lg:px-18 lg:py-16">

                <!-- Slot -->
                <slot></slot>

            </article>

        </section>

        <!-- Footer -->
        <v-footer></v-footer>

        <!-- Advert -->
        <v-advert v-if="advert"></v-advert>

    </main>
</template>

<script>
    import FooterPartial from './footer/index.vue';
    import HeaderPartial from './header/index.vue';
    import SidebarPartial from './sidebar/index.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-footer'  : FooterPartial,
            'v-header'  : HeaderPartial,
            'v-sidebar' : SidebarPartial,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'container' : { type : Boolean, default : true },
        },

        /**
         * Execute actions when the component is created.
         *
         */
        created()
        {
            document.querySelector('title').innerHTML = this.prop('title');
        },

		/**
		 * Execute actions when the component is mounted to the DOM.
		 *
		 */
		mounted()
		{
            if (this.prop('notification')) {
                this.notify(this.prop('notification.type'), this.prop('notification.message'));
            }
		},

        /**
         * Execute actions when the component is unmounted from the DOM.
         *
         */
        unmounted()
        {
            if (window.session.hasOwnProperty('ads')) {
                delete window.session.ads;
            }
        },
    }
</script>

<style>
    #nprogress .bar { height: 3px !important }
    #nprogress .bar .peg { @apply shadow-none }
</style>