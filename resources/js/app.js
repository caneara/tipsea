/**
 * Import the dependencies.
 *
 */
import '../css/app.css';
import Theme from '../js/theme';
import { createApp, h, reactive } from 'vue';
import Application from '@/mixins/Application';
import { InertiaProgress } from '@inertiajs/progress';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

/**
 * Configure the XHR progress bar.
 *
 */
InertiaProgress.init({
    color       : '#ECC94B',
    includeCSS  : true,
    showSpinner : false,
});

/**
 * Configure the history event listener.
 *
 */
window.addEventListener('popstate', (event) => {
    event.stopImmediatePropagation();

    document.body.style.overflow = 'visible';

    app.config.globalProperties.$inertia.reload({
        preserveState  : false,
        preserveScroll : false,
        replace        : true,
        onSuccess      : (page) => app.config.globalProperties.$inertia.setPage(page),
        onError        : () => location.href = event.state.url,
    });
});

/**
 * Configure the DOM ready event listener.
 *
 */
window.addEventListener('DOMContentLoaded', () =>
{
    Theme.apply();

    let path = (name) => resolvePageComponent(
        `../vue/pages/${name}.vue`, import.meta.glob('../vue/pages/**/*.vue')
    );

    createInertiaApp({
        resolve : name => path(name),
        setup({ el, App, props, plugin })
        {
            window.app = createApp({ render : () => h(App, {
                initialPage      : JSON.parse(document.getElementById('app').dataset.page),
                resolveComponent : name => path(name).then(module => {
                    window.session = window.session ??= reactive({ }); return module.default;
                }),
            }) });

            window.app.mixin(Application);

            window.app.use(plugin).mount(el);
        },
    });
});