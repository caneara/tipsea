<template>
    <div class="flex gap-x-6">

        <!-- Likes -->
        <div class="flex items-center"
             :dusk="`metrics_likes_${tip?.id}`"
             @click="canLike() ? $emit('like') : null"
             :class="canLike() ? 'cursor-pointer' : ''">

            <!-- Icon -->
            <i class="fas fa-heart text-11px text-red-700/50 dark:text-red-400/50 mr-2"></i>

            <!-- Name -->
            <span class="text-12px text-gray-600/80 dark:text-gray-500">
                {{ number(tip?.metrics?.likes ?? 0) }}
            </span>

        </div>

        <!-- Comments -->
        <div class="flex items-center"
             :dusk="`metrics_comments_${tip?.id}`">

            <!-- Icon -->
            <i class="fas fa-comments text-11px text-sky-700/50 dark:text-sky-400/50 mr-2"></i>

            <!-- Name -->
            <span class="text-12px text-gray-600/80 dark:text-gray-500">
                {{ number(tip?.metrics?.comments ?? 0) }}
            </span>

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
        emits : ['like'],

        /**
         * Define the public properties.
         *
         */
        props : {
            'tip' : { type : Object, default : {} },
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Determine whether the user is permitted to like the tip.
             *
             */
            canLike()
            {
                if (this.isGuest()) return false;

                let owner = this.tip?.user?.id ?? this.tip.user_id;

                return owner !== this.prop('user.id');
            },
        }
    }
</script>