export default
{
    /**
     * Define the supporting methods.
     *
     */
    methods :
    {
        /**
         * Send an XHR request.
         *
         */
        http() { return new class
        {
            /**
             * Send an XHR request to the given URL.
             *
             */
            async _send(url, format, options = {})
            {
                let response = await fetch(url, options);

                switch (format) {
                    case 'json': return await response.json();
                    case 'text': return await response.text();
                    case 'blob': return await response.blob();
                }
            }

            /**
             * Send an XHR 'delete' request to the given URL.
             *
             */
            async delete(url, data = {}, format = 'json', options = {})
            {
                data._method = 'delete';

                let defaults = {
                    method      : 'post',
                    credentials : 'same-origin',
                    headers     : this.headers(),
                    body        : JSON.stringify(data),
                }

                return this._send(url, format, Object.assign(defaults, options));
            }

            /**
             * Retrieve the default headers to include with each request.
             *
             */
            headers()
            {
                return {
                    'Content-Type'     : 'application/json',
                    'X-Requested-With' : 'XMLHttpRequest',
                    'X-CSRF-TOKEN'     : window.app.config.globalProperties.$page.props.csrf,
                };
            }

            /**
             * Send an XHR 'get' request to the given URL.
             *
             */
            async get(url, format, options = {})
            {
                return this._send(url, format, options);
            }

            /**
             * Send an XHR 'post' request to the given URL.
             *
             */
            async post(url, data = {}, format = 'json', options = {})
            {
                let defaults = {
                    method      : 'post',
                    credentials : 'same-origin',
                    headers     : this.headers(),
                    body        : JSON.stringify(data),
                }

                return this._send(url, format, Object.assign(defaults, options));
            }
        }},
    }
}