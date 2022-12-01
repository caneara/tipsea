<template>
    <v-container>

        <!-- Left -->
        <template #left>

            <!-- Title -->
            <h6>
                Internet
            </h6>

            <!-- Text -->
            <p class="mb-4 md:mb-7">
                Add your professional website and any social
                media accounts that you use.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-4">
                When adding any links, you should include
                the full url e.g. https://www.example.com
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-0">
                Your donation link e.g. PayPal, Patreon etc.
                will be displayed alongside your tips.
            </p>

        </template>

        <!-- Right -->
        <template #right>

            <!-- Website -->
            <v-textbox id="website"
                       class="mb-4"
                       label="Website"
                       :optional="true"
                       :maxLength="100"
                       icon="fas fa-globe"
                       v-model="form.website"
                       :error="form.errors.website">
            </v-textbox>

            <!-- Donate -->
            <v-textbox id="donate"
                       class="mb-4"
                       :optional="true"
                       :maxLength="100"
                       label="Donation link"
                       v-model="form.donate"
                       icon="fas fa-sack-dollar"
                       :error="form.errors.donate">
            </v-textbox>

            <!-- Github -->
            <v-textbox id="github"
                       class="mb-4"
                       label="Github"
                       :optional="true"
                       :maxLength="100"
                       icon="fab fa-github"
                       v-model="form.github"
                       :error="form.errors.github">
            </v-textbox>

            <!-- Twitter -->
            <v-textbox class="mb-4"
                       id="twitter"
                       label="Twitter"
                       :optional="true"
                       :maxLength="100"
                       icon="fab fa-twitter"
                       v-model="form.twitter"
                       :error="form.errors.twitter">
            </v-textbox>

            <!-- LinkedIn -->
            <v-textbox class="mb-4"
                       id="linkedin"
                       :optional="true"
                       :maxLength="100"
                       label="LinkedIn"
                       icon="fab fa-linkedin"
                       v-model="form.linkedin"
                       :error="form.errors.linkedin">
            </v-textbox>

            <!-- YouTube -->
            <v-textbox id="youtube"
                       class="mb-4"
                       label="YouTube"
                       :optional="true"
                       :maxLength="100"
                       icon="fab fa-youtube"
                       v-model="form.youtube"
                       :error="form.errors.youtube">
            </v-textbox>

            <!-- Facebook -->
            <v-textbox class="mb-4"
                       id="facebook"
                       :optional="true"
                       :maxLength="100"
                       label="Facebook"
                       icon="fab fa-facebook"
                       v-model="form.facebook"
                       :error="form.errors.facebook">
            </v-textbox>

            <!-- Action -->
            <div class="md:flex justify-end">
                <v-button label="Update"
                          id="save-internet"
                          :processing="form.processing"
                          @click="submitForm(form, 'account.update', 'patch', { preserveScroll : true })">
                </v-button>
            </div>

        </template>

    </v-container>
</template>

<script>
    import ContainerComponent from '@/components/container.vue';
    import ButtonComponent from '@caneara/varnish/src/components/button.vue';
    import TextBoxComponent from '@caneara/varnish/src/components/textbox.vue';

    export default
    {
        /**
         * Define the components.
         *
         */
        components : {
            'v-button'    : ButtonComponent,
            'v-container' : ContainerComponent,
            'v-textbox'   : TextBoxComponent,
        },

        /**
         * Define the data model.
         *
         */
        data() { return {
            form : this.createForm({
                website  : this.prop('user.website'),
                donate   : this.prop('user.donate'),
                github   : this.prop('user.github'),
                twitter  : this.prop('user.twitter'),
                linkedin : this.prop('user.linkedin'),
                youtube  : this.prop('user.youtube'),
                facebook : this.prop('user.facebook'),
            }),
        }},
    }
</script>