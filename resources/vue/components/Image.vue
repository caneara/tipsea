<template>
    <img :src="url"
         :alt="title"
         :title="title"
         @error="$el.src = fallback">
</template>

<script>
    export default
    {
        /**
         * Define the public properties.
         *
         */
        props : {
            'fallback' : { type : String, default : '' },
            'title'    : { type : String, default : '' },
            'url'      : { type : String, default : '' },
        },
    }
</script>
