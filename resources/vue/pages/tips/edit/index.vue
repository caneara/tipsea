<template>
    <v-layout>

        <!-- Header -->
        <div class="flex justify-between mb-7">

            <!-- Title -->
            <h2 class="mb-0">
                Edit Tip
            </h2>

            <!-- Menu -->
            <v-menu id="tip"
                    :items="context()">
            </v-menu>

        </div>

        <!-- Summary -->
        <p class="-mb-1 lg:-mb-4 xl:-mb-8">
            Revise an existing code tip. You can also adjust its scheduled publication,
            including whether it is posted to social media platforms that you have connected
            TipSea to (note that this assumes the tip has not already been published).
        </p>

        <!-- Form -->
        <v-form action="update"></v-form>

    </v-layout>
</template>

<script>
    import Layout from '@/layout/index.vue';
    import FormPartial from '../form/index.vue';
    import MenuComponent from '@caneara/varnish/src/components/menu.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-form'   : FormPartial,
            'v-layout' : Layout,
            'v-menu'   : MenuComponent,
        },

        /**
         * Execute actions when the component is created.
         *
         */
        created()
        {
            window.session.form = this.createForm({
                banner_id    : this.prop('tip.banner_id'),
                title        : this.prop('tip.title'),
                summary      : this.prop('tip.summary'),
                teaser       : this.prop('tip.teaser'),
                theme        : this.prop('tip.theme'),
                gradient     : this.prop('tip.gradient'),
                card         : this.prop('tip.card'),
                first_tag    : this.prop('tip.first_tag'),
                second_tag   : this.prop('tip.second_tag'),
                third_tag    : this.prop('tip.third_tag'),
                fourth_tag   : this.prop('tip.fourth_tag'),
                content      : this.prop('tip.content'),
                attribution  : this.prop('tip.attribution'),
                shared       : this.prop('tip.shared'),
                published_at : this.prop('published') ? null : this.prop('tip.published_at'),
                publication  : this.prop('tip.published_at') ? 'schedule' : 'draft',
            });
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
                        action : () => this.share(this.route('tips.show', this.prop('tip.slug'))),
                        icon   : 'fas fa-share',
                        id     : `share-${this.prop('tip.id')}`,
                        label  : 'Share tip',
                        show   : true,
                        type   : 'link',
                    }, {
                        show   : true,
                        type   : 'separator',
                    }, {
                        action : () => this.redirect(['tips.show', this.prop('tip.slug')]),
                        icon   : 'fas fa-eye',
                        id     : `show-${this.prop('tip.id')}`,
                        label  : 'View tip',
                        show   : true,
                        type   : 'link',
                    }, {
                        show   : true,
                        type   : 'separator',
                    }, {
                        action : () => this.deleteTip(),
                        icon   : 'fas fa-trash-alt',
                        id     : `delete-${this.prop('tip.id')}`,
                        label  : 'Delete tip',
                        show   : true,
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
        }
    }
</script>