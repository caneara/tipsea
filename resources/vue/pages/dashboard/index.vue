<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Dashboard
        </h2>

        <!-- Onboarding -->
        <v-onboarding v-if="! prop('tips.data').filter(item => ! item?.deleted ?? false).length"></v-onboarding>

        <!-- Summary -->
        <p v-else
           class="mb-10">

            <!-- Text -->
            Review the latest code tips and tricks from the people that you are currently
            following. If you're looking for more insightful code tips and tricks, then try

            <!-- Link -->
            <v-link :href="route('home')">
                following other
            </v-link>

            <!-- Text -->
            authors.

        </p>

        <!-- Tips -->
        <v-tips :items="prop('tips')"
                :deleteOnRemoveFollower="true">
        </v-tips>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import OnboardingPartial from './onboarding.vue';
    import TipComponent from '@/components/tips.vue';
    import LinkComponent from '@/components/link.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-layout'     : Layout,
            'v-link'       : LinkComponent,
            'v-onboarding' : OnboardingPartial,
            'v-tips'       : TipComponent,
        },
    }
</script>