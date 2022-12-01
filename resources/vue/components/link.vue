<template>
    <Link :title="title"
          rel="noopener"
          :target="target">

        <!-- Slot -->
        <slot></slot>

    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            Link,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'target' : { type : String, default : '_self' },
            'title'  : { type : String, default : '' },
        },
    }
</script>