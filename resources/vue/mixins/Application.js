import Form from './Form';
import Http from './Http';
import Images from './Images';
import Utilities from './Utilities';
import Formatting from './Formatting';
import Authentication from './Authentication';
import Dialog from '@caneara/varnish/src/mixins/Dialog';

export default
{
    /**
     * Define the mixins.
     *
     */
    mixins : [
        Form,
        Http,
        Images,
        Dialog,
        Utilities,
        Formatting,
        Authentication,
    ],

    /**
     * Define the computed properties.
     *
     */
    computed :
    {
        /**
         * Retrieve the current session.
         *
         */
        session()
        {
            return window.session;
        }
    },

    /**
     * Define the supporting methods.
     *
     */
    methods :
    {
        /**
         * Retrieve a reference to the application instance or sub-property.
         *
         */
        app(prop = null)
        {
            return prop
                ? window.app.config.globalProperties[`$${prop}`]
                : window.app.config.globalProperties;
        },

        /**
         * Generate a url to the given application asset.
         *
         */
        asset(path)
        {
            return `${this.prop('asset')}${path}`;
        },

        /**
         * Retrieve or set the Inertia property with the given key.
         *
         */
        prop(key, fallback = '')
        {
            return Array.isArray(key)
                ? this.set(this.app('page').props, key[0], key[1])
                : this.get(this.app('page').props, key, fallback);
        },

        /**
         * Send the user to the route with the given configuration.
         *
         */
        redirect(config, options = { preserveScroll : false }, external = false, tab = false)
        {
            config = Array.isArray(config) ? config : [config];

            return external
                ? (tab ? window.open(config[0], '_blank') : window.location.href = config[0])
                : this.app('inertia').get(route(...config), {}, options);
        },

        /**
         * Generate a route using the given parameters.
         *
         */
        route(...parameters)
        {
            return parameters.length ? window.route(...parameters) : window.route();
        },

        /**
         * Generate a url to the given storage file.
         *
         */
        storage(file)
        {
            return `${this.prop('storage')}${file}`;
        },
    }
}