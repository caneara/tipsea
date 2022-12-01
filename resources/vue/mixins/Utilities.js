export default
{
    /**
     * Define the supporting methods.
     *
     */
    methods :
    {
        /**
         * Determine if the given value is empty.
         *
         */
        blank(value)
        {
            if (Array.isArray(value)) {
                return value.length === 0;
            }

            if (value instanceof Date) {
                return false;
            }

            if (typeof value === 'object' && value !== null) {
                return Object.keys(value).length === 0 &&
                    Object.getOwnPropertyNames(value).length === 0;
            }

            return ['', null, undefined].includes(value);
        },

        /**
         * Retrieve a deeply-nested property from the given target.
         *
         */
        get(target, key, fallback = '')
        {
            return key.split('.').reduce((p, c) => p && p[c], target) ?? fallback;
        },

        /**
         * Retrieve the query string, or a specific item within it.
         *
         */
        queryString(key = null)
        {
            let query = new window.URLSearchParams(document.location.search);

            return key ? query.get(key) : query;
        },

        /**
         * Load an external JavaScript file or library.
         *
         */
        script(url)
        {
            let scripts = document.getElementsByTagName('script');

            if (! this.blank(Array.from(scripts).filter(item => item.src === url))) return;

            let script = document.createElement('script');

            script.src = url;

            document.getElementsByTagName('head')[0].appendChild(script);
        },

        /**
         * Assign a deeply-nested property on the given target.
         *
         */
        set(target, key, value)
        {
            key.split('.').reduce(function(p, c, i) {
                return (i + 1 == key.split('.').length) ? p[c] = value : p[c] = p[c] || {};
            }, target);
        },

        /**
         * Execute the given closure when the given conditional closure evaluates to true.
         *
         */
        when(conditional, closure)
        {
            (async() => {
                while(! conditional()) {
                    await new Promise((resolve, reject) => setTimeout(resolve, 50));
                }

                return closure();
            })();
        },
    }
}