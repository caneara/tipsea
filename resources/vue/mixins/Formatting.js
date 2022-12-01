export default
{
    /**
     * Define the supporting methods.
     *
     */
    methods :
    {
        /**
         * Process the given date and time to indicate how much time has passed.
         *
         */
        age(value)
        {
            let divisions = [
                { name : 'seconds', amount : 60 },
                { name : 'minutes', amount : 60 },
                { name : 'hours',   amount : 24 },
                { name : 'days',    amount : 7 },
                { name : 'weeks',   amount : 4.34524 },
                { name : 'months',  amount : 12 },
                { name : 'years',   amount : Number.POSITIVE_INFINITY },
            ];

            let formatter = new Intl.RelativeTimeFormat('en-US', { style : 'long' });

            let duration = ((typeof value === 'string' ? new Date(value) : value) - new Date()) / 1000;

            for (let i = 0; i <= divisions.length; i++) {
                if (Math.abs(duration) < divisions[i].amount) {
                    return formatter.format(Math.round(duration), divisions[i].name);
                } else {
                    duration /= divisions[i].amount;
                }
            }
        },

        /**
         * Process the given date into its correct format.
         *
         */
        date(value)
        {
            let options = {
                day   : 'numeric',
                month : 'short',
                year  : 'numeric',
            };

            value = typeof value === 'string' ? new Date(value.split('.')[0]) : value;

            return new Intl.DateTimeFormat('en-US', options).format(value);
        },

        /**
         * Process the given date and time into its correct format.
         *
         */
        dateTime(value)
        {
            return `${this.date(value)} - ${this.time(value)}`;
        },

        /**
         * Generate a metric using the given value and noun.
         *
         */
        metric(value, noun, suffix = 's')
        {
            return `${this.number(value)} ${parseInt(value) === 1 ? noun : `${noun}${suffix}`}`;
        },

        /**
         * Process the given financial value into its correct format.
         *
         */
        money(value, currency = 'USD')
        {
            let options = {
                style                 : 'currency',
                currency              : currency,
                minimumFractionDigits : 2,
                maximumFractionDigits : 2,
            };

            return new Intl.NumberFormat('en-US', options).format(value);
        },

        /**
         * Process the given numerical value into its correct format.
         *
         */
        number(value, type = 'integer')
        {
            let options = {
                integer : {
                    notation       : 'compact',
                    compactDisplay : 'short',
                },
                decimal : {
                    minimumFractionDigits : 2,
                    maximumFractionDigits : 2,
                }
            };

            return new Intl.NumberFormat('en-US', options[type]).format(value);
        },

        /**
         * Retrieve the pluralized or singular form of the given word based on the given count.
         *
         */
        pluralize(total, word, suffix = 's')
        {
            return total === 1 ? word : `${word}${suffix}`;
        },

        /**
         * Process the given time into its correct format.
         *
         */
        time(value)
        {
            let options = {
                hour12 : false,
                hour   : '2-digit',
                minute : '2-digit',
                dayPeriod: 'short',
            };

            value = typeof value === 'string' ? new Date(value) : value;

            let meridiem = value.toLocaleString(
                'en-US', { hour : 'numeric', hour12 : true }
            ).split(' ')[1].toLowerCase();

            return `${new Intl.DateTimeFormat('en-UK', options).format(value)} (${meridiem})`;
        },
    }
}