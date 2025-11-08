<?php

namespace App\Livewire\Traits;

trait HasFlashMessages
{
    /**
     * Flash a success message
     *
     * @param string $message
     * @return void
     */
    protected function flashSuccess(string $message): void
    {
        session()->flash('success', $message);
    }

    /**
     * Flash an error message
     *
     * @param string $message
     * @return void
     */
    protected function flashError(string $message): void
    {
        session()->flash('error', $message);
    }

    /**
     * Flash an info message
     *
     * @param string $message
     * @return void
     */
    protected function flashInfo(string $message): void
    {
        session()->flash('info', $message);
    }

    /**
     * Flash a warning message
     *
     * @param string $message
     * @return void
     */
    protected function flashWarning(string $message): void
    {
        session()->flash('warning', $message);
    }
}
