<template>
    <v-container type="vertical"
                 v-if="! prop('published')">

        <!-- Title -->
        <h6>
            Publication
        </h6>

        <!-- Information -->
        <p class="mb-7">
            Specify whether you want to save the tip as a draft, or whether
            you are ready to release / schedule it. Note that once a tip has
            been saved with a publish date, it cannot be reverted to a draft.
        </p>

        <!-- Options -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4"
             :class="! update || prop('tip.published_at') === '' ? 'xl:grid-cols-3' : ''">

            <!-- Draft -->
            <div @click="publication('draft')"
                 dusk="card_publication_draft"
                 v-if="! update || prop('tip.published_at') === ''"
                 class="border rounded-md cursor-pointer transition duration-300 p-6"
                 :class="session.form.publication === 'draft' ? 'bg-sky-50/50 dark:bg-sky-400/10 border-sky-600/50 dark:border-sky-600/70' : 'hover:bg-sky-50/50 dark:hover:bg-sky-400/10 border-gray-300 dark:border-gray-600 hover:border-sky-600/50 dark:hover:border-sky-600/70'">

                <!-- Header -->
                <div class="flex items-center mb-6">

                    <!-- Icon -->
                    <i class="fas fa-edit text-20px text-emerald-600/70 dark:text-emerald-500/70"></i>

                    <!-- Title -->
                    <h6 class="text-gray-800 dark:text-gray-300 ml-14px mb-0">
                        Draft
                    </h6>

                </div>

                <!-- Summary -->
                <p class="text-15px mb-0">
                    You're currently working on this tip and are not ready
                    to publish it on TipSea or on social media.
                </p>

            </div>

            <!-- Release -->
            <div @click="publication('release')"
                 dusk="card_publication_release"
                 class="border rounded-md cursor-pointer transition duration-300 p-6"
                 :class="session.form.publication === 'release' ? 'bg-sky-50/50 dark:bg-sky-400/10 border-sky-600/50 dark:border-sky-600/70' : 'hover:bg-sky-50/50 dark:hover:bg-sky-400/10 border-gray-300 dark:border-gray-600 hover:border-sky-600/50 dark:hover:border-sky-600/70'">

                <!-- Header -->
                <div class="flex items-center mb-6">

                    <!-- Icon -->
                    <i class="fas fa-paper-plane text-20px text-sky-600/70 dark:text-sky-500/70"></i>

                    <!-- Title -->
                    <h6 class="text-gray-800 dark:text-gray-300 ml-14px mb-0">
                        Release
                    </h6>

                </div>

                <!-- Summary -->
                <p class="text-15px mb-0">
                    Publish the tip on TipSea (and any other connected social media
                    accounts) right away.
                </p>

            </div>

            <!-- Schedule -->
            <div @click="publication('schedule')"
                 dusk="card_publication_schedule"
                 class="border rounded-md cursor-pointer transition duration-300 p-6"
                 :class="session.form.publication === 'schedule' ? 'bg-sky-50/50 dark:bg-sky-400/10 border-sky-600/50 dark:border-sky-600/70' : 'hover:bg-sky-50/50 dark:hover:bg-sky-400/10 border-gray-300 dark:border-gray-600 hover:border-sky-600/50 dark:hover:border-sky-600/70'">

                <!-- Header -->
                <div class="flex items-center mb-6">

                    <!-- Icon -->
                    <i class="fas fa-calendar-alt text-20px text-purple-600/70 dark:text-purple-400/70"></i>

                    <!-- Title -->
                    <h6 class="text-gray-800 dark:text-gray-300 ml-14px mb-0">
                        Schedule
                    </h6>

                </div>

                <!-- Summary -->
                <p class="text-15px mb-0">
                    Publish on TipSea (and any other connected social media
                    accounts) at a specific time.
                </p>

            </div>

        </div>

        <!-- DateTime -->
        <div class="flex flex-col md:items-start xl:items-end">

            <!-- Published At -->
            <v-datetime type="datetime"
                        :meridiem="true"
                        id="published_at"
                        label="Date & time"
                        icon="fas fa-calendar"
                        v-model="session.form.published_at"
                        v-if="session.form.publication === 'schedule'"
                        :minDate="new Date().toISOString().split('T')[0]"
                        class="w-full md:w-1/2 xl:w-auto xl:min-w-350px md:pr-2 xl:pr-0 mt-4">
            </v-datetime>

            <!-- Error -->
            <v-error class="my-4"
                     :value="session.form.errors.published_at">
            </v-error>

        </div>

        <!-- Submit -->
        <v-submit v-if="update"
                  id="save-publication">
        </v-submit>

    </v-container>
</template>

<script>
    import SubmitPartial from './submit.vue';
    import ContainerComponent from '@/components/container.vue';
    import ErrorComponent from '@caneara/varnish/src/components/error.vue';
    import DateTimeComponent from '@caneara/varnish/src/components/datetime.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-container' : ContainerComponent,
            'v-datetime'  : DateTimeComponent,
            'v-error'     : ErrorComponent,
            'v-submit'    : SubmitPartial,
        },

        /**
         * Define the public properties.
         *
         */
        props : {
            'update' : { type : Boolean, default : false },
        },

        /**
         * Define the supporting methods.
         *
         */
        methods :
        {
            /**
             * Change the publication option for the current tip.
             *
             */
            publication(option)
            {
                let date = new Date();

                date.setTime(date.getTime() + 86_400_000);

                if (['draft', 'release'].includes(option)) {
                    window.session.form.published_at = '';
                }

                if (option === 'schedule') {
                    window.session.form.published_at = date.toISOString();
                }

                window.session.form.publication = option;
            },
        }
    }
</script>