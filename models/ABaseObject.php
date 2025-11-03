<?php

declare(strict_types=1);

namespace models;

use models\interfaces\Observer;

/**
 * Abstract base object with observer pattern support
 * 
 * Provides common functionality for model objects including:
 * - Error handling
 * - Observer pattern implementation
 * - Result management
 * - Cache management
 */
abstract class ABaseObject
{
    protected array $errors = [];
    protected mixed $result = false;
    /**
     * @var Observer[]
     */
    protected array $observers = [];
    protected ?object $model_ar = null;

    /**
     * Get operation result
     */
    public function getResult(): mixed
    {
        return $this->result;
    }

    /**
     * Get errors array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Post-build hook method
     * 
     * Executes after build operation completes
     * Notifies observers and clears cache
     */
    protected function afterBuild(): void
    {
        $this->notifyObservers();
        $this->deleteCache();
    }

    /**
     * Add observer to the object
     * 
     * @param Observer $observer The observer instance to add
     */
    public function addObserver(Observer $observer): self
    {
        $this->observers[] = $observer;

        return $this;
    }

    /**
     * Notify all registered observers
     * 
     * Uses either getObjectObserver() method result or model_ar property
     * as the object to pass to observers
     */
    public function notifyObservers(): self
    {
        if (!empty($this->observers)) {
            $objectObs = method_exists($this, 'getObjectObserver') 
                ? $this->getObjectObserver() 
                : null;

            if (!$objectObs && $this->model_ar) {
                $objectObs = $this->model_ar;
            }

            if (!$objectObs) {
                return $this;
            }

            foreach ($this->observers as $observer) {
                $observer->notify($objectObs);
            }
        }

        return $this;
    }

    /**
     * Get associated ActiveRecord model
     */
    public function getModelAr(): ?object
    {
        return $this->model_ar;
    }

    /**
     * Delete cached data
     * 
     * Abstract method to be implemented by child classes
     * for cache invalidation logic
     */
    abstract protected function deleteCache(): void;
}