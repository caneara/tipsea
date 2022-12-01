<template>
    <div class="tips">

        <!-- Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-15">

            <!-- Tips -->
            <div :key="item.id"
                 v-for="item in items.data.filter(item => ! item?.deleted ?? false)">

                <!-- Teaser -->
                <v-link :href="route('tips.show', item.slug)">
                    <v-image :fallback="tipCard()"
                             :url="tipCard(item.card)"
                             class="w-full h-200px rounded-lg object-cover object-bottom">
                    </v-image>
                </v-link>

                <!-- Tags -->
                <v-tags :tip="item"></v-tags>

                <!-- Tip -->
                <div class="flex-1">

                    <!-- Link -->
                    <v-link class="flex flex-col lg:flex-row lg:items-start"
                            :href="route('tips.show', item.slug)">

                        <!-- Status -->
                        <v-badge value="Draft"
                                 color="purple"
                                 v-if="! item.published_at"
                                 class="relative top-2px mb-5 md:mb-4 lg:mb-0 lg:mr-10px">
                        </v-badge>

                        <!-- Title -->
                        <h5 class="text-17px dark:text-gray-400 leading-snug clamp two-lines mb-5px">
                            {{ item.title }}
                        </h5>

                    </v-link>

                    <!-- Summary -->
                    <p class="text-15px leading-relaxed md:clamp three-lines">
                        {{ item.summary }}
                    </p>

                    <!-- Footer -->
                    <div class="flex items-center justify-between">

                        <!-- User -->
                        <div class="flex items-center">

                            <!-- Avatar -->
                            <v-link :href="route('profile', item.handle)">
                                <v-image :title="item.user"
                                        :fallback="userAvatar()"
                                        :url="userAvatar(item.avatar)"
                                        class="h-30px rounded-full mr-3">
                                </v-image>
                            </v-link>

                            <!-- Name -->
                            <v-link :href="route('profile', item.handle)">
                                <span class="text-13px text-gray-600/70 dark:text-gray-500">
                                    {{ item.user }}
                                </span>
                            </v-link>

                        </div>

                        <!-- Meta -->
                        <div class="flex items-center">

                            <!-- Metrics -->
                            <v-metrics :tip="item"
                                       class="mr-4"
                                       @like="toggleLike(item)">
                            </v-metrics>

                            <!-- Menu -->
                            <v-menu id="tip"
                                    :items="context(item)">
                            </v-menu>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Paginator -->
        <v-paginator source="tips"
                     v-if="showPaginator"
                     @change="mergePaginatorContent($event)">
        </v-paginator>

    </div>
</template>

<script>
    import Pagination from '@/mixins/pagination';
    import TagComponent from '@/components/tags.vue';
    import LinkComponent from '@/components/link.vue';
    import ImageComponent from '@/components/image.vue';
    import MetricComponent from '@/components/metrics.vue';
    import PaginatorComponent from '@/components/paginator.vue';
    import MenuComponent from '@caneara/varnish/src/components/menu.vue';
    import BadgeComponent from '@caneara/varnish/src/components/badge.vue';

    export default
    {
        /**
         * Define the mixins.
         *
         */
        mixins : [
            Pagination,
        ],

        /**
         * Define the components.
         *
         */
        components : {
            'v-badge'     : BadgeComponent,
            'v-image'     : ImageComponent,
            'v-link'      : LinkComponent,
            'v-menu'      : MenuComponent,
            'v-metrics'   : MetricComponent,
            'v-paginator' : PaginatorComponent,
            'v-tags'      : TagComponent,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'deleteOnRemoveBookmark'  : { type : Boolean, default : false },
            'deleteOnRemoveFollower'  : { type : Boolean, default : false },
            'showFollowerMenuOption'  : { type : Boolean, default : true },
            'showPaginator'           : { type : Boolean, default : true },
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Retrieve the menu options for the given item.
             *
             */
            context(item)
            {
                return [
                    {
                        action : () => this.toggleLike(item),
                        icon   : 'fas fa-heart',
                        id     : `like-${item.id}`,
                        label  : `${item.liked ? 'Remove like' : 'Like tip'}`,
                        show   : this.isAuthenticated() && ! this.isOwner(item),
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && ! this.isOwner(item),
                        type   : 'separator',
                    }, {
                        action : () => this.share(this.route('tips.show', item.slug)),
                        icon   : 'fas fa-share',
                        id     : `share-${item.id}`,
                        label  : 'Share tip',
                        show   : true,
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && this.isOwner(item),
                        type   : 'separator',
                    }, {
                        action : () => this.redirect(['tips.edit', item.id]),
                        icon   : 'fas fa-edit',
                        id     : `edit-${item.id}`,
                        label  : 'Edit tip',
                        show   : this.isAuthenticated() && this.isOwner(item),
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && (this.isOwner(item) || this.prop('user.type') === 2),
                        type   : 'separator',
                    }, {
                        action : () => this.deleteItem(item),
                        icon   : 'fas fa-trash-alt',
                        id     : `delete-${item.id}`,
                        label  : 'Delete tip',
                        show   : this.isAuthenticated() && (this.isOwner(item) || this.prop('user.type') === 2),
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && ! this.isOwner(item),
                        type   : 'separator',
                    }, {
                        action : () => this.toggleBookmark(item),
                        icon   : 'fas fa-book-bookmark',
                        id     : `bookmark-${item.id}`,
                        label  : `${item.bookmarked ? 'Remove bookmark' : 'Bookmark tip'}`,
                        show   : this.isAuthenticated() && ! this.isOwner(item),
                        type   : 'link',
                    }, {
                        show   : this.isAuthenticated() && this.showFollowerMenuOption && ! this.isOwner(item),
                        type   : 'separator',
                    }, {
                        action : () => this.toggleFollower(item),
                        icon   : 'fas fa-user',
                        id     : `follower-${item.id}`,
                        label  : `${item.follower ? 'Stop' : 'Start'} following user`,
                        show   : this.isAuthenticated() && this.showFollowerMenuOption && ! this.isOwner(item),
                        type   : 'link',
                    },
                ];
            },

            /**
             * Delete the given item.
             *
             */
            async deleteItem(item)
            {
                if (await this.confirm()) {
                    this.submitForm(null, ['tips.delete', item.id], 'delete');
                }
            },

            /**
             * Determine whether the user is the teacher of the given item.
             *
             */
            isOwner(item)
            {
                if (this.isGuest()) return false;

                return item.user_id === this.prop('user.id');
            },

            /**
             * Toggle whether the user has bookmarked the given item.
             *
             */
            toggleBookmark(item)
            {
                item.bookmarked
                    ? this.http().delete(route('bookmarks.delete', item.id))
                    : this.http().post(route('bookmarks.store', item.id));

                item.bookmarked = ! item.bookmarked;

                item.deleted = ! item.bookmarked && this.deleteOnRemoveBookmark;

                this.notify('success', `The bookmark has been ${item.bookmarked ? 'created' : 'deleted'}`);

                this.updatePaginatorContent(item);
            },

            /**
             * Toggle whether the user is following the teacher of the given item.
             *
             */
            toggleFollower(item)
            {
                item.follower
                    ? this.http().delete(route('follower.delete', item.user_id))
                    : this.http().post(route('follower.store', item.user_id));

                item.follower = ! item.follower;

                item.deleted = ! item.follower && this.deleteOnRemoveFollower;

                this.notify('success', `The user has been ${item.follower ? 'followed' : 'unfollowed'}`);

                this.updatePaginatorContent(item);
            },

            /**
             * Toggle whether the user has liked the given item.
             *
             */
            toggleLike(item)
            {
                item.liked
                    ? this.http().delete(route('likes.delete', item.id))
                    : this.http().post(route('likes.store', item.id));

                item.liked = ! item.liked;

                let total = this.get(item, 'metrics.likes', 0);

                this.set(item, 'metrics.likes', item.liked ? total + 1 : total - 1);

                this.notify('success', `The tip has been ${item.liked ? 'liked' : 'unliked'}`);
            },
        }
    }
</script>