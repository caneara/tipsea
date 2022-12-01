<template>
    <div>

        <!-- Header -->
        <div class="flex justify-between mb-1">

            <!-- Title -->
            <h2 class="leading-tight mb-0">
                {{ prop('tip.title') }}
            </h2>

            <!-- Menu -->
            <v-menu id="tip"
                    :items="context()">
            </v-menu>

        </div>

        <!-- Meta -->
        <div class="flex items-center mb-8">

            <!-- Date -->
            <div class="mr-4">

                <!-- Icon -->
                <i class="fas fa-calendar-alt text-12px text-purple-600/60 dark:text-purple-400/70 relative -top-1px mr-2"></i>

                <!-- Text -->
                <span class="text-14px text-gray-600/70 dark:text-gray-500">
                    {{ prop('tip.published_at') ? age(prop('tip.published_at')) : 'Draft' }}
                </span>

            </div>

            <!-- Reading Time -->
            <div class="mr-4">

                <!-- Icon -->
                <i class="fas fa-book-open text-12px text-emerald-600/60 dark:text-emerald-400/70 mr-2"></i>

                <!-- Text -->
                <span class="text-14px text-gray-600/70 dark:text-gray-500">
                    {{ estimatedReadingTime() }} minute read
                </span>

            </div>

        </div>

        <!-- Attribution -->
        <v-notice format="icon"
                  type="warning"
                  class="mt-10 mb-8"
                  v-if="prop('tip.attribution')">

            <!-- Text -->
            This tip is a repost. It originally came from here&hellip;

            <!-- Link -->
            <a rel="noopener"
               target="_blank"
               :href="prop('tip.attribution')">

                <!-- Text -->
                {{ prop('tip.attribution') }}

            </a>

        </v-notice>

        <!-- Content -->
        <div class="overflow-auto">
            <v-writer class="public"
                      :readingMode="true"
                      v-model="$inertia.page.props.tip.content">
            </v-writer>
        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center mb-4">

            <!-- Tags -->
            <v-tags :tip="prop('tip')"></v-tags>

            <!-- Metrics -->
            <v-metrics :tip="prop('tip')"
                       @like="toggleLike(prop('tip'))">
            </v-metrics>

        </div>

    </div>
</template>

<script>
    import '@caneara/varnish/src/styles/writer.css';
    import TagComponent from '@/components/tags.vue';
    import MetricComponent from '@/components/metrics.vue';
    import MenuComponent from '@caneara/varnish/src/components/menu.vue';
    import NoticeComponent from '@caneara/varnish/src/components/notice.vue';
    import WriterComponent from '@caneara/varnish/src/components/writer.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-menu'    : MenuComponent,
            'v-metrics' : MetricComponent,
            'v-notice'  : NoticeComponent,
            'v-tags'    : TagComponent,
            'v-writer'  : WriterComponent,
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Retrieve the menu options.
             *
             */
            context()
            {
                return [
                    {
                        action : () => this.toggleLike(),
                        icon   : 'fas fa-heart',
                        id     : `like-${this.prop('tip.id')}`,
                        label  : `${this.prop('liked') ? 'Remove like' : 'Like tip'}`,
                        show   : this.isAuthenticated() && ! this.isOwner(),
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && ! this.isOwner(),
                        type   : 'separator',
                    }, {
                        action : () => this.share(this.route('tips.show', this.prop('tip.slug'))),
                        icon   : 'fas fa-share',
                        id     : `share-${this.prop('tip.id')}`,
                        label  : 'Share tip',
                        show   : true,
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && this.isOwner(),
                        type   : 'separator',
                    }, {
                        action : () => this.redirect(['tips.edit', this.prop('tip.id')]),
                        icon   : 'fas fa-edit',
                        id     : `edit-${this.prop('tip.id')}`,
                        label  : 'Edit tip',
                        show   : this.isAuthenticated() && this.isOwner(),
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && (this.isOwner() || this.prop('user.type') === 2),
                        type   : 'separator',
                    }, {
                        action : () => this.deleteTip(),
                        icon   : 'fas fa-trash-alt',
                        id     : `delete-${this.prop('tip.id')}`,
                        label  : 'Delete tip',
                        show   : this.isAuthenticated() && (this.isOwner() || this.prop('user.type') === 2),
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && ! this.isOwner(),
                        type   : 'separator',
                    }, {
                        action : () => this.toggleBookmark(),
                        icon   : 'fas fa-book-bookmark',
                        id     : `bookmark-${this.prop('tip.id')}`,
                        label  : `${this.prop('bookmarked') ? 'Remove bookmark' : 'Bookmark tip'}`,
                        show   : this.isAuthenticated() && ! this.isOwner(),
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && ! this.isOwner(),
                        type   : 'separator',
                    }, {
                        action : () => this.toggleFollower(),
                        icon   : 'fas fa-user',
                        id     : `follower-${this.prop('tip.id')}`,
                        label  : `${this.prop('follower') ? 'Stop' : 'Start'} following user`,
                        show   : this.isAuthenticated() && ! this.isOwner(),
                        type   : 'link',
                    },
                ];
            },

            /**
             * Delete the current tip.
             *
             */
            async deleteTip()
            {
                if (await this.confirm()) {
                    this.submitForm(null, ['tips.delete', this.prop('tip.id')], 'delete');
                }
            },

            /**
             * Calculate the average amount of time to read the tip.
             *
             */
            estimatedReadingTime()
            {
                return Math.ceil(this.prop('tip.content').trim().split(/\s+/).length / 255);
            },

            /**
             * Determine whether the user is the owner of the tip.
             *
             */
            isOwner()
            {
                if (this.isGuest()) return false;

                return this.prop('tip.user.id') === this.prop('user.id');
            },

            /**
             * Toggle whether the user has bookmarked the tip.
             *
             */
            toggleBookmark()
            {
                this.prop('bookmarked')
                    ? this.http().delete(route('bookmarks.delete', this.prop('tip.id')))
                    : this.http().post(route('bookmarks.store', this.prop('tip.id')));

                this.prop(['bookmarked', ! this.prop('bookmarked')]);

                this.notify('success', `The bookmark has been ${this.prop('bookmarked') ? 'created' : 'deleted'}`);
            },

            /**
             * Toggle whether the user is following the teacher of the tip.
             *
             */
            toggleFollower()
            {
                this.prop('follower')
                    ? this.http().delete(route('follower.delete', this.prop('tip.user.id')))
                    : this.http().post(route('follower.store', this.prop('tip.user.id')));

                this.prop(['follower', ! this.prop('follower')]);

                this.notify('success', `The user has been ${this.prop('follower') ? 'followed' : 'unfollowed'}`);
            },

            /**
             * Toggle whether the user has liked the tip.
             *
             */
            toggleLike()
            {
                this.prop('liked')
                    ? this.http().delete(route('likes.delete', this.prop('tip.id')))
                    : this.http().post(route('likes.store', this.prop('tip.id')));

                this.prop(['liked', ! this.prop('liked')]);

                let total = this.prop('tip.metrics.likes');

                this.prop(['tip.metrics.likes', this.prop('liked') ? total + 1 : total - 1]);

                this.notify('success', `The tip has been ${this.prop('liked') ? 'liked' : 'unliked'}`);
            },
        }
    }
</script>