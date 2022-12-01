import { useForm } from '@inertiajs/inertia-vue3';

export default
{
    /**
     * Define the supporting methods.
     *
     */
    methods :
    {
        /**
         * Create an Inertia form.
         *
         */
        createForm(data = {}, key = '', remember = true)
        {
            key = key ? key : parseInt(window.performance.now());

            return useForm(data, { key : key, remember : remember });
        },

        /**
         * Submit the given Inertia form to the server.
         *
         */
        submitForm(form = null, url, method = 'post', options = {})
        {
            form = form ??= this.createForm();

            form.clearErrors();

            form._token = this.prop('csrf');

            document.body.style.overflow = 'auto';

            url = Array.isArray(url) ? url : [url];

            if (method !== 'get') {
                let verb = method;

                form = form.transform((data) => ({
                    ...data, '_method' : verb, '_token' : this.prop('csrf'),
                }));

                method = 'post';
            }

            let defaults = {
                headers        : {
                    'Accept'           : 'application/json',
                    'Content-Type'     : 'application/json',
                    'X-Requested-With' : 'XMLHttpRequest',
                    'X-CSRF-TOKEN'     : this.prop('csrf'),
                },
                preserveState  : 'errors',
                preserveScroll : 'errors',
            };

            return form.submit(method, window.route(...url), Object.assign(defaults, options));
        }
    }
}