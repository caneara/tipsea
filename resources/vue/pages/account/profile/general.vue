<template>
    <v-container>

        <!-- Left -->
        <template #left>

            <!-- Title -->
            <h6>
                General
            </h6>

            <!-- Information -->
            <p class="mb-4 md:mb-7">
                Update your main details, such as
                your name and email address.
            </p>

            <!-- Tip -->
            <p class="text-14px text-gray-500/80 dark:text-gray-500/80 mb-0">
                Changing your username will alter the url
                used for your public profile. You should
                only change it if you really need to.
            </p>

        </template>

        <!-- Right -->
        <template #right>

            <!-- Name -->
            <v-textbox id="name"
                       class="mb-4"
                       label="Name"
                       :maxLength="50"
                       autocomplete="name"
                       v-model="form.name"
                       icon="fas fa-signature"
                       :error="form.errors.name">
            </v-textbox>

            <!-- Handle -->
            <v-textbox id="handle"
                       class="mb-4"
                       :maxLength="30"
                       label="Username"
                       icon="fas fa-user"
                       v-model="form.handle"
                       :error="form.errors.handle">
            </v-textbox>

            <!-- Email Address -->
            <v-textbox id="email"
                       class="mb-4"
                       :maxLength="255"
                       autocomplete="email"
                       label="Email address"
                       v-model="form.email"
                       icon="fas fa-envelope"
                       :error="form.errors.email">
            </v-textbox>

            <!-- Biography -->
            <v-textbox :lines="5"
                       class="mb-4"
                       id="biography"
                       :maxLength="500"
                       label="Biography"
                       v-model="form.biography"
                       icon="fas fa-graduation-cap"
                       :error="form.errors.biography">
            </v-textbox>

            <!-- Action -->
            <div class="md:flex justify-end">
                <v-button label="Update"
                          id="save-general"
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
                name      : this.prop('user.name'),
                handle    : this.prop('user.handle'),
                email     : this.prop('user.email'),
                biography : this.prop('user.biography'),
            }),
        }},
    }
</script>