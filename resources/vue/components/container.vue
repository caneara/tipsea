<template>
    <div :class="border ? '' : 'first-of-type:border-t-0 first-of-type:pt-0'"
         class="border-t border-gray-300/50 dark:border-gray-700/70 pt-11 md:pt-15 lg:pt-17 xl:pt-20 mt-12 md:mt-15 lg:mt-17 xl:mt-20">

        <!-- Vertical -->
        <div v-if="type === 'vertical'">
            <slot></slot>
        </div>

        <!-- Horizontal -->
        <div v-else
             class="flex flex-wrap">

            <!-- Left Side -->
            <div class="w-full md:w-1/2 lg:w-9/20 md:pr-18 lg:pr-9 xl:pr-18 pb-6 md:pb-0">
                <slot name="left"></slot>
            </div>

            <!-- Right Side -->
            <div class="w-full md:w-1/2 lg:w-11/20 md:pl-10">
                <slot name="right"></slot>
            </div>

        </div>

    </div>
</template>

<script>
    export default
    {
        /**
         * Define the public properties.
         *
         */
        props : {
            'border' : { type : Boolean, default : false },
            'type'   : { type : String,  default : 'horizontal' },
        },
    }
</script>