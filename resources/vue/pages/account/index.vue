<template>
    <v-layout>

        <!-- Header -->
        <h2>
            Account
        </h2>

        <!-- Summary -->
        <p class="mb-7">
            Manage your main account details, such as your name, email
            address and photo, as well as your public profile information,
            such as your website and social media accounts.
        </p>

        <!-- Tabs -->
        <v-tabs id="panels"
                :items="tabs"
                v-model="active">
        </v-tabs>

        <!-- Content -->
        <div class="mt-10">
            <component :is="`v-${active}`"
                       @tab="switchTab($event)">
            </component>
        </div>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import DeletePartial from './delete/index.vue';
    import ProfilePartial from './profile/index.vue';
    import PasswordPartial from './password/index.vue';
    import SettingsPartial from './settings/index.vue';
    import TabsComponent from '@caneara/varnish/src/components/tabs.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-delete'   : DeletePartial,
            'v-profile'  : ProfilePartial,
            'v-layout'   : Layout,
            'v-password' : PasswordPartial,
            'v-settings' : SettingsPartial,
            'v-tabs'     : TabsComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            active : 'profile',

            tabs : [
                { id : 'profile',  icon : 'fas fa-user',      label : 'Profile' },
                { id : 'settings', icon : 'fas fa-cog',       label : 'Settings' },
                { id : 'password', icon : 'fas fa-key',       label : 'Password' },
                { id : 'delete',   icon : 'fas fa-trash-alt', label : 'Delete' },
            ],
        }},

        /**
         * Execute actions when the component is created.
         *
         */
        created()
        {
            let requested = this.queryString('tab');

            if (['profile', 'settings', 'password', 'delete'].includes(requested)) {
                return this.active = requested;
            }

            this.active = localStorage.getItem('varnish_tabs_panels') ?? 'profile';
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Change the currently active tab.
             *
             */
            switchTab(tab)
            {
                this.active = tab;

                localStorage.setItem('varnish_tabs_panels', tab);
            },
        },
    }
</script>