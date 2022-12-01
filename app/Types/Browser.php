<?php

namespace App\Types;

use App\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser as BaseBrowser;

class Browser extends BaseBrowser
{
    /**
     * Assert that the given checkbox is checked.
     *
     */
    public function assertChecked($field, $value = null) : static
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        return $this->assertInputValue($field, $value);
    }

    /**
     * Assert that the given field has the given date and time.
     *
     */
    public function assertDateTime(string $field, Carbon $value) : static
    {
        return $this->assertInputValue($field, $value->format("Y-m-d\TH:i:s.000\Z"));
    }

    /**
     * Assert that the given input field has the given value.
     *
     */
    public function assertInputValue($field, $value) : static
    {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        return parent::assertInputValue($field, $value);
    }

    /**
     * Assert that the given dropdown has the given value selected.
     *
     */
    public function assertSelected($field, $value) : static
    {
        if (is_bool($value)) {
            $value = (int) $value;
        }

        return parent::assertSelected($field, $value);
    }

    /**
     * Assert that the given title is the same as the page's title.
     *
     */
    public function assertTitle($title) : static
    {
        $name = config('app.name');

        $title = $title === $name ? $name : "{$name} - {$title}";

        $this->pause();

        return parent::assertTitle($title);
    }

    /**
     * Attach the file at the given path to the given field.
     *
     */
    public function attach($field, $path) : static
    {
        $field = Str::start($field, '@');

        return parent::attach($field, $path)->pause(2000);
    }

    /**
     * Check the given checkbox.
     *
     */
    public function check($field, $value = null) : static
    {
        return $this->click($field);
    }

    /**
     * Click on the given Dusk selector.
     *
     */
    public function click($selector = null) : static
    {
        $selector = Str::startsWith($selector, '|') ? substr($selector, 1) : Str::start($selector, '@');

        return parent::click($selector)->pause(500);
    }

    /**
     * Accept the confirmation prompt.
     *
     */
    public function confirm() : static
    {
        parent::assertSee('Are you sure you wish to proceed?');

        return $this->push('dialog_confirm_continue')->pause(1000);
    }

    /**
     * Select the given date for the given field.
     *
     */
    public function date(string $field, string | Carbon $value) : static
    {
        $value = is_string($value) ? $value : $value->toW3cString();

        return $this->javascript("events.emit('set-date-for-{$field}', '{$value}')");
    }

    /**
     * Select the given date and time for the given field.
     *
     */
    public function dateTime(string $field, Carbon $value) : static
    {
        return $this->click("{$field}_text_box")
            ->pause()
            ->select("{$field}_select_year", $value->year)
            ->pause()
            ->select("{$field}_select_month", $value->month)
            ->pause()
            ->click("{$field}_select_day_{$value->day}")
            ->pause()
            ->select("{$field}_select_hour", $value->hour)
            ->pause()
            ->select("{$field}_select_minute", $value->minute)
            ->pause();
    }

    /**
     * Assign the given value to the browser's local storage.
     *
     */
    public function localStorage(string $key, string $value) : static
    {
        return $this->javascript("localStorage.setItem('{$key}', '{$value}')");
    }

    /**
     * Execute the given JavaScript code.
     *
     */
    public function javascript(string $code) : static
    {
        return (tap($this)->script($code))->pause();
    }

    /**
     * Perform a lookup using the given parameters.
     *
     */
    public function lookup(string $field, string $search, $value, bool $assert = true) : static
    {
        $this->type("{$field}_search_display", $search)
            ->pause()
            ->click("lookup-${field}-item-$value");

        return $assert ? $this->assertInputValue($field, $value) : $this;
    }

    /**
     * Open the given menu and click on the given option.
     *
     */
    public function menu(string $name, string $option) : static
    {
        return $this->click("menu-{$name}")
            ->click("menu-{$name}-{$option}");
    }

    /**
     * Pause for the given amount of milliseconds.
     *
     */
    public function pause($milliseconds = 500) : static
    {
        return parent::pause($milliseconds);
    }

    /**
     * Press the given button.
     *
     */
    public function push(string $field, int $pause = 1000) : static
    {
        return $this->click($field)->pause($pause);
    }

    /**
     * Scroll to the top of the page.
     *
     */
    public function scrollToTop() : static
    {
        return $this->javascript('scrollTo(0, 0)');
    }

    /**
     * Select the given value.
     *
     */
    public function select($field, $value = null) : static
    {
        if (is_bool($value)) {
            $value = (int) $value;
        }

        return parent::select($field, $value);
    }

    /**
     * Switch to the given menu tab.
     *
     */
    public function switchTab(string $name) : static
    {
        return $this->scrollToTop()
            ->push("select-tab-{$name}")
            ->pause();
    }

    /**
     * Insert the given value into the tags element.
     *
     */
    public function tag(string $field, string $value = '', bool $reset = false) : static
    {
        $field = Str::startsWith($field, '|') ? substr($field, 1) : Str::start($field, '@');

        if ($reset) {
            $this->within($field, fn($browser) => $browser->keys('.tagify__input', '{backspace}'))
                ->within($field, fn($browser) => $browser->keys('.tagify__input', '{backspace}'))
                ->within($field, fn($browser) => $browser->keys('.tagify__input', '{backspace}'))
                ->within($field, fn($browser) => $browser->keys('.tagify__input', '{backspace}'));
        }

        return $this->within($field, fn($browser) => $browser->keys('.tagify__input', $value))
            ->within($field, fn($browser) => $browser->keys('.tagify__input', '{tab}'));
    }

    /**
     * Select the given time for the given field.
     *
     */
    public function time(string $field, string | Carbon $value) : static
    {
        $value = is_string($value) ? new Carbon($value) : $value;

        return $this->select("{$field}_hour", $value->hour)
            ->select("{$field}_minute", $value->minute);
    }

    /**
     * Type the given value into the given field.
     *
     */
    public function type($field, $value) : static
    {
        $this->script("document.querySelector('[dusk={$field}]').value = ''");
        $this->script("document.querySelector('[dusk={$field}]').dispatchEvent(new Event('input'))");

        return parent::type($field, (string) $value);
    }

    /**
     * Browse to the given route.
     *
     */
    public function visitRoute($route, $parameters = []) : static
    {
        return parent::visitRoute($route, $parameters)
            ->pause(3000);
    }
}
