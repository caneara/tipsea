<template>
    <div class="comments">

        <!-- Items -->
        <div :key="comment.id"
             v-if="prop('comments.data').length"
             :class="index === 0 ? 'pb-6' : 'py-6'"
             v-for="(comment, index) in prop('comments.data')">

            <!-- Item -->
            <div class="flex"
                 :class="comment.depth === 0 ? '' : (comment.depth === 1 ? 'pl-4 md:pl-30px' : 'pl-8 md:pl-60px')">

                <!-- Left -->
                <div class="mr-6px md:mr-2">

                    <!-- Avatar -->
                    <v-link :href="route('profile', comment.handle)">
                        <v-image :title="comment.name"
                                 :fallback="userAvatar()"
                                 :url="userAvatar(comment.avatar)"
                                 class="w-30px md:w-50px rounded-full">
                        </v-image>
                    </v-link>

                </div>

                <!-- Right -->
                <div class="flex-1 ml-3">

                    <!-- Header -->
                    <div class="flex justify-between">

                        <!-- Author -->
                        <v-link :href="route('profile', comment.handle)">
                            <h6 class="font-medium text-gray-800 dark:text-gray-300 flex justify-center md:justify-start items-center mb-0">
                                {{ comment.name }}
                            </h6>
                        </v-link>

                        <!-- Actions -->
                        <v-menu id="comment"
                                v-if="isAuthenticated()"
                                :items="context(comment)">
                        </v-menu>

                    </div>

                    <!-- Date -->
                    <div class="mt-3px mb-5">

                        <!-- Icon -->
                        <i class="fas fa-calendar-alt text-11px text-purple-600/60 dark:text-purple-400/70 relative -top-1px mr-2"></i>

                        <!-- Text -->
                        <span class="text-13px text-gray-600/70 dark:text-gray-500">
                            {{ age(comment.created_at) }}
                        </span>

                    </div>

                    <!-- Message -->
                    <p class="break mb-0">
                        {{ comment.message }}
                    </p>

                </div>

            </div>

        </div>

        <!-- Actions -->
        <div v-if="isAuthenticated()"
             class="md:flex justify-end mt-8">

            <!-- Create -->
            <v-button label="Create"
                      mode="outline"
                      @click="addComment()">
            </v-button>

        </div>

    </div>
</template>

<script>
    import LinkComponent from '@/components/link.vue';
    import ImageComponent from '@/components/image.vue';
    import MenuComponent from '@caneara/varnish/src/components/menu.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button' : ButtonComponent,
            'v-image'  : ImageComponent,
            'v-link'   : LinkComponent,
            'v-menu'   : MenuComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                message : '',
            })
        }},

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Add a new comment.
             *
             */
            async addComment()
            {
                await this.promptForInput('Add a new comment');

                if (this.form.message) {
                    this.submitForm(this.form, ['comments.store', this.prop('tip.id')], 'post', { preserveScroll : true });
                }
            },

            /**
             * Retrieve the menu options for the given comment.
             *
             */
            context(comment)
            {
                return [
                    {
                        id     : `reply-${comment.id}`,
                        icon   : 'fas fa-plus',
                        show   : this.prop('user.id') !== comment.user_id && comment.depth < 2,
                        type   : 'link',
                        label  : 'Reply to comment',
                        action : () => this.replyComment(comment),
                    }, {
                        id     : `edit-${comment.id}`,
                        icon   : 'fas fa-edit',
                        show   : this.prop('user.id') === comment.user_id,
                        type   : 'link',
                        label  : 'Edit comment',
                        action : () => this.editComment(comment),
                    }, {
                        show   : this.prop('user.id') === comment.user_id,
                        type   : 'separator',
                    }, {
                        id     : `delete-${comment.id}`,
                        icon   : 'fas fa-trash-alt',
                        show   : this.prop('user.id') === comment.user_id,
                        type   : 'link',
                        label  : 'Delete comment',
                        action : () => this.deleteComment(comment),
                    }
                ];
            },

            /**
             * Delete the given comment.
             *
             */
            async deleteComment(comment)
            {
                if (await this.confirm()) {
                    this.submitForm(this.form, ['comments.delete', comment.id], 'delete', { preserveScroll : true });
                }
            },

            /**
             * Edit the given comment.
             *
             */
            async editComment(comment)
            {
                await this.promptForInput('Edit an existing comment', comment.message);

                if (this.form.message && this.form.message !== comment.message) {
                    this.submitForm(this.form, ['comments.update', comment.id], 'patch', { preserveScroll : true });
                }
            },

            /**
             * Request input from the user.
             *
             */
            async promptForInput(title, fallback = '')
            {
                this.form.errors.message = '';

                this.form.message = await this.prompt(
                    title,
                    'Further the tip conversation by adding or updating a reply. Please remember to be respectful and on-topic.',
                    'Message',
                    fallback,
                    10,
                    500
                );
            },

            /**
             * Reply to the given comment.
             *
             */
            async replyComment(comment)
            {
                await this.promptForInput('Reply to a comment');

                if (this.form.message) {
                    this.submitForm(this.form, ['comments.reply', comment.id], 'post', { preserveScroll : true });
                }
            },
        }
    }
</script>