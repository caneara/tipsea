<template>
    <div class="hidden -mt-7">

        <!-- Structure & Rules -->
        <div class="varnish-writer">
            <div class="varnish-container">
                <div class="varnish-preview overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Data type</th>
                                <th>Min chars</th>
                                <th>Max chars</th>
                                <th>Nullable</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>title</td>
                                <td>String</td>
                                <td>5</td>
                                <td>100</td>
                                <td>❌</td>
                            </tr>
                            <tr>
                                <td>summary</td>
                                <td>String</td>
                                <td>5</td>
                                <td>200</td>
                                <td>❌</td>
                            </tr>
                            <tr>
                                <td>teaser</td>
                                <td>Markdown</td>
                                <td>100</td>
                                <td>1000</td>
                                <td>❌</td>
                            </tr>
                            <tr>
                                <td>first_tag</td>
                                <td>String</td>
                                <td>1</td>
                                <td>20</td>
                                <td>❌</td>
                            </tr>
                            <tr>
                                <td>second_tag</td>
                                <td>String</td>
                                <td>1</td>
                                <td>20</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>third_tag</td>
                                <td>String</td>
                                <td>1</td>
                                <td>20</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>fourth_tag</td>
                                <td>String</td>
                                <td>1</td>
                                <td>20</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>content</td>
                                <td>Markdown</td>
                                <td>100</td>
                                <td>5000</td>
                                <td>❌</td>
                            </tr>
                            <tr>
                                <td>attribution</td>
                                <td>URL</td>
                                <td>11</td>
                                <td>100</td>
                                <td>✅</td>
                            </tr>
                            <tr>
                                <td>published_at</td>
                                <td>String (Date)</td>
                                <td>24</td>
                                <td>24</td>
                                <td>❌</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notice -->
        <v-notice class="mb-8">
            The value you supply for 'published_at' must be an ISO 8601 UTC String e.g. "2022-06-13T08:30:00.000Z".
            In addition, the date must be in the past (ideally it should be more than one day old).
        </v-notice>

        <!-- Example -->
        <pre class="bg-gray-100 rounded overflow-auto">
            <div class="-ml-30 -mt-3 -mb-10">
                [{
                    "title"        : "Indexing database queries",
                    "summary"      : "Excepturi impedit totam culpa aut excepturi omnis molestiae. Dolor nihil et et aperiam eligendi in.",
                    "teaser"       : "Atque eveniet provident numquam ad quae quia rem. Aut sint dolorem error nostrum voluptatem aut sint ullam.",
                    "first_tag"    : "Python",
                    "second_tag"   : "JavaScript",
                    "third_tag"    : "",
                    "content"      : "Voluptatibus totam eaque dolorem harum in doloribus. Autem sunt id exercitationem a. Iusto iusto explicabo ea non aut.",
                    "attribution"  : "https://developer.site/articles/indexing-database-queries",
                    "published_at" : "2022-06-13T08:30:00.000Z"
                }]
            </div>
        </pre>

    </div>
</template>

<script>
    import NoticeComponent from '@caneara/varnish/src/components/notice.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-notice' : NoticeComponent,
        },

		/**
		 * Execute actions when the component is mounted to the DOM.
		 *
		 */
		mounted()
		{
            this.$el.style.maxWidth = `${this.$el.parentNode.getBoundingClientRect().width}px`;

            this.$el.classList.remove('hidden');
		},
    }
</script>
