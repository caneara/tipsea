/**
 * Assign the chosen theme to the application shell.
 *
 */
export default function apply()
{
    document.body.addEventListener('keydown', event => {
        if (event.key === 't' && event.target === document.body) {
            document.body.classList.toggle('dark')
        }
    });

    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.body.classList.add('dark');
    }

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event =>
        document.body.classList.toggle('dark')
    );
};