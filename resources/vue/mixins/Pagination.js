export default
{
    /**
     * Define the public properties.
     *
     */
    props : {
        'items' : { type : Object, default : {} },
    },

    /**
     * Define the supporting methods.
     *
     */
    methods :
    {
        /**
         * Insert new items into the existing paginator instance.
         *
         */
        mergePaginatorContent(paginator)
        {
            this.items.current_page = paginator.current_page;
            this.items.next_page_url = paginator.next_page_url;

            paginator.data.forEach(item => this.items.data.push(item));
        },

        /**
         * Update the main item collection with the given revised item.
         *
         */
        updatePaginatorContent(revised)
        {
            let index = this.items.data.findIndex(i => i.id === revised.id);

            this.items.data[index] = revised;
        },
    }
}