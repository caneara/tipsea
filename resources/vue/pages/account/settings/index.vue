<template>
    <div>
        <v-integration></v-integration>
        <v-notifications></v-notifications>
    </div>
</template>

<script>
    import IntegrationPartial from './integration.vue';
    import NotificationPartial from './notifications.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-integration'   : IntegrationPartial,
            'v-notifications' : NotificationPartial,
        },
    }
</script>