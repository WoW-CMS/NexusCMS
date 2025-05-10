<?php

namespace App\Traits;

use InvalidArgumentException;

trait RouteViewHandler
{
    /**
     * Available views/actions for the controller
     *
     * @var array
     */
    protected $views = [
        'index' => 'index',
        'show' => 'list'
    ];

    /**
     * Current view/action
     *
     * @var string
     */
    protected $currentView = 'index';

    /**
     * Set current view
     *
     * @param string $view
     * @return self
     * @throws \InvalidArgumentException
     */
    protected function setView(string $view): self
    {
        if (!array_key_exists($view, $this->views)) {
            throw new \InvalidArgumentException("Vista '{$view}' no soportada");
        }

        $this->currentView = $view;
        return $this;
    }

    /**
     * Get current view template
     *
     * @return string
     */
    protected function getCurrentView(): string
    {
        return $this->views[$this->currentView];
    }

    /**
     * Guess the view name based on controller and action
     *
     * @return string
     */
    protected function guessViewName(): string
    {
        $controllerName = class_basename($this);
        $controllerName = str_replace('Controller', '', $controllerName);
        return strtolower($controllerName) . '.' . $this->getCurrentView();
    }

    /**
     * Guess the redirect route based on controller and action
     *
     * @param string $action
     * @return string
     */
    protected function guessRedirectRoute(string $action): string
    {
        $controllerName = class_basename($this);
        $controllerName = str_replace('Controller', '', $controllerName);
        return strtolower($controllerName) . '.' . $action;
    }
}
