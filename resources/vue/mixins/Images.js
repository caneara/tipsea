export default
{
    /**
     * Define the supporting methods.
     *
     */
    methods :
    {
        /**
         * Construct a url to an image using the given parameters.
         *
         */
        _image(id = null, resource = '')
        {
            return id ? this.storage(`images/${id}.jpg`) : this.asset(`img/${resource}`);
        },

        /**
         * Construct a url to the given banner graphic.
         *
         */
        bannerGraphic(id = null)
        {
            return this._image(id, 'banner.png');
        },

        /**
         * Construct a url to the given tip card.
         *
         */
        tipCard(id = null)
        {
            return this._image(id, 'tip.png');
        },

        /**
         * Construct a url to the given user avatar.
         *
         */
        userAvatar(id = null)
        {
            return this._image(id, 'user.png');
        },
    }
}