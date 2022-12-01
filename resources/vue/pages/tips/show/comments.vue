<template>
    <div :class="prop('related.data').length ? 'mt-12' : 'mt-8'"
         class="border-t border-slate-400/30 dark:border-slate-600/30 pt-12">

        <!-- Title -->
        <h5 class="mb-10">
            Comments
        </h5>

        <!-- Empty -->
        <v-empty :visible="! prop('comments.data').length"
                 message="This tip does not have any comments">
        </v-empty>

        <!-- Items -->
        <v-items></v-items>

        <!-- Pagination -->
        <v-paginator source="comments"
                     @change="mergePaginatorContent($event)">
        </v-paginator>

    </div>
</template>

<script>
    import ItemPartial from './items.vue';
    import Pagination from '@/mixins/pagination';
    import PaginatorComponent from '@/components/paginator.vue';
    import EmptyComponent from '@caneara/varnish/src/components/empty.vue';

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
            'v-empty'     : EmptyComponent,
            'v-items'     : ItemPartial,
            'v-paginator' : PaginatorComponent,
        },
    }
</script>