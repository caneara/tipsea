<template>
    <div>

        <!-- Notice -->
        <v-notice class="lg:hidden">

            <!-- Text -->
            Are you looking for your

            <!-- Link -->
            <v-link class="text-purple-600 dark:text-purple-400"
                    :href="route('profile', { user : prop('user.handle') })">

                <!-- Text -->
                public profile?

            </v-link>

        </v-notice>

        <!-- Sections -->
        <div>
            <v-general></v-general>
            <v-avatar></v-avatar>
            <v-internet></v-internet>
        </div>

    </div>
</template>

<script>
    import AvatarPartial from './avatar.vue';
    import GeneralPartial from './general.vue';
    import InternetPartial from './internet.vue';
    import LinkComponent from '@/components/link.vue';
    import NoticeComponent from '@caneara/varnish/src/components/notice.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-avatar'   : AvatarPartial,
            'v-general'  : GeneralPartial,
            'v-internet' : InternetPartial,
            'v-link'     : LinkComponent,
            'v-notice'   : NoticeComponent,
        },
    }
</script>