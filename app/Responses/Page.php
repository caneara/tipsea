<?php

namespace App\Responses;

use App\Types\Model;
use Inertia\Inertia;
use Inertia\Response;

class Page
{
    /**
     * The page description.
     *
     */
    protected string $description = 'The smart way to write, share and discover the latest code tips & tricks.';

    /**
     * The page meta tags.
     *
     */
    protected array $meta = [];

    /**
     * The page robots.
     *
     */
    protected string $robots = '';

    /**
     * The page title.
     *
     */
    protected string $title = 'TipSea';

    /**
     * The page view.
     *
     */
    protected string $view = '';

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->meta = $this->defaultMetaTags();

        $this->robots = $this->requiresAuthentication() ? 'NOINDEX, NOFOLLOW' : 'INDEX, FOLLOW';
    }

    /**
     * Assign a description to the page.
     *
     */
    public function description(string $text) : static
    {
        $this->description = $text;

        return $this;
    }

    /**
     * Retrieve the default meta tags to use for the page.
     *
     */
    protected function defaultMetaTags() : array
    {
        return [
            'title'       => $this->title,
            'description' => $this->description,
            'type'        => 'website',
            'url'         => request()->url(),
            'image'       => asset('img/card.png'),
            'twitter'     => [
                'type'    => 'summary_large_image',
            ],
        ];
    }

    /**
     * Determine if the current route requires an authenticated user.
     *
     */
    protected function requiresAuthentication() : bool
    {
        $middleware = request()->route()?->controllerMiddleware() ?? [];

        return in_array('auth', $middleware);
    }

    /**
     * Factory method.
     *
     */
    public static function make() : static
    {
        return new static();
    }

    /**
     * Assign custom meta tags to the page.
     *
     */
    public function meta(Model $model) : static
    {
        $this->meta = Meta::create($model);

        return $this;
    }

    /**
     * Assign a robot configuration to the page.
     *
     */
    public function render() : Response
    {
        Inertia::setRootView('app.index');

        return Inertia::render($this->view)
            ->with('title', $this->title)
            ->withViewData('meta', $this->meta)
            ->withViewData('title', $this->title)
            ->withViewData('robots', $this->robots)
            ->withViewData('description', $this->description);
    }

    /**
     * Assign a robots configuration to the page.
     *
     */
    public function robots(string $text) : static
    {
        $this->robots = $text;

        return $this;
    }

    /**
     * Assign a title to the page.
     *
     */
    public function title(string $text) : static
    {
        $this->title = "TipSea - {$text}";

        return $this;
    }

    /**
     * Assign a view to the page.
     *
     */
    public function view(string $text) : static
    {
        $this->view = str_replace('.', '/', $text);

        return $this;
    }

    /**
     * Assign the given value to the page.
     *
     */
    public function with(string $key, mixed $value) : Response
    {
        return $this->render()->with($key, $value);
    }

    /**
     * Remove all SEO-based meta tags from the page.
     *
     */
    public function withoutMeta() : static
    {
        $this->meta = [];

        return $this;
    }

    /**
     * Assign the given value to the page's view data.
     *
     */
    public function withViewData(string $key, mixed $value) : Response
    {
        return $this->render()->withViewData($key, $value);
    }
}
