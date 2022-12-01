<template>
    <div class="screenshot">

        <!-- Writer -->
        <v-writer id="teaser"
                  :error="error"
                  v-if="editable"
                  :toolbar="false"
                  v-model="content"
                  :maxLength="1000"
                  class="public mb-4"
                  @change="$emit('content', $event)"
                  placeholder="Summarise your tip to catch eyes...">
        </v-writer>

        <!-- Content -->
        <div class="overflow-auto h-400px relative rounded mb-4">

            <!-- Theme -->
            <div title="Toggle between light and dark mode"
                 @click="$emit('theme', theme === 'light' ? 'dark' : 'light')"
                 class="bg-black/20 rounded-lg cursor-pointer absolute top-14px right-14px z-2 p-2">

                <!-- Icon -->
                <i class="fas fa-fw text-20px text-gray-300"
                   :class="theme === 'dark' ? 'fa-sun' : 'fa-moon'">
                </i>

            </div>

            <!-- Window -->
            <div :class="gradients[gradient - 1]"
                 class="window bg-gradient-to-tl font-safe w-full min-w-[856px] h-400px overflow-hidden absolute z-1 px-16 pt-16">

                <!-- Chrome -->
                <div :class="themes[theme].title_bar"
                     class="border-b flex items-center h-12 rounded-t-lg relative overflow-hidden p-4">

                    <!-- Traffic Lights -->
                    <div class="flex items-center gap-2 absolute">
                        <div class="bg-red-500 w-3 h-3 rounded-full"></div>
                        <div class="bg-yellow-400 w-3 h-3 rounded-full"></div>
                        <div class="bg-green-400 w-3 h-3 rounded-full"></div>
                    </div>

                    <!-- Title -->
                    <div :class="themes[theme].title_text"
                         class="w-full font-medium text-19px text-center truncate h-20px relative top-1px mx-20">

                        <!-- Text -->
                        {{ title }}

                    </div>

                </div>

                <!-- Code -->
                <div class="h-full px-8 pt-7 pb-0"
                     :class="themes[theme].background">

                    <!-- Rendering -->
                    <v-writer class="public"
                              v-model="content"
                              :readingMode="true">
                    </v-writer>

                </div>

            </div>

        </div>

        <!-- Hint -->
        <div class="flex justify-center items-center xl:hidden mb-6">

            <!-- Icon -->
            <i class="fas fa-arrow-left text-12px text-gray-500"></i>

            <!-- Text -->
            <span class="font-medium text-12px text-gray-500 uppercase mx-3">
                Scroll Preview
            </span>

            <!-- Icon -->
            <i class="fas fa-arrow-right text-12px text-gray-500"></i>

        </div>

        <!-- Gradient Palette -->
        <div class="grid grid-cols-6 md:grid-cols-12 gap-4 mb-10">
            <div :class="gradient"
                 @click="$emit('gradient', index + 1)"
                 v-for="(gradient, index) in gradients"
                 class="bg-gradient-to-tl rounded-md cursor-pointer p-3">
            </div>
        </div>

    </div>
</template>

<script>
    import WriterComponent from '@caneara/varnish/src/components/writer.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-writer' : WriterComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            gradients : [
                'from-sky-800 to-sky-400',
                'from-pink-400 to-purple-500',
                'from-green-400 to-blue-500',
                'from-blue-400 to-purple-500',
                'from-yellow-400 to-red-500',
                'from-emerald-700 to-emerald-300',
                'from-cyan-400 to-sky-500',
                'from-orange-400 to-pink-600',
                'from-green-400 to-cyan-500',
                'from-purple-500 to-indigo-500',
                'from-pink-500 to-rose-500',
                'from-lime-300 to-emerald-500',
            ],

            themes : {
                light : {
                    background : 'bg-white',
                    title_bar  : 'bg-gray-100 border-gray-300',
                    title_text : 'text-gray-700',
                },
                dark : {
                    background : 'bg-gray-800 dark',
                    title_bar  : 'bg-gray-900 border-transparent',
                    title_text : 'text-gray-400',
                },
            },
        }},

        /**
         * Define the events.
         *
         */
        emits : ['content', 'gradient', 'theme'],

        /**
         * Define the public properties.
         *
         */
        props : {
            'content'  : { type : String,  default : '' },
            'editable' : { type : Boolean, default : true },
            'error'    : { type : String,  default : '' },
            'gradient' : { type : Number,  default : 1 },
            'theme'    : { type : String,  default : 'dark' },
            'title'    : { type : String,  default : '' },
        },
    }
</script>