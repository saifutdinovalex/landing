<?php

declare(strict_types=1);

namespace models;

use models\response\Response;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Abstract base model with response handling capabilities
 * 
 * Extends AbstractModel to provide standardized response formatting
 * and data management for API responses
 */
abstract class AbstractModelBase extends AbstractModel
{
    /**
     * @var array Response data that will be merged with the standard response
     */
    protected array $data_response = [];

    /**
     * Get validation rules for the model
     * 
     * Returns parent class rules by default. Child classes should override
     * this method to define their specific validation rules.
     * 
     * @return array Validation rules
     */
    public function rules(): array
    {
        return parent::rules();
    }

    /**
     * Build standardized API response
     * 
     * Creates a consistent response format with status code and data.
     * Automatically includes success message and any additional data
     * set via setDataResponse() method.
     * 
     * @return array Formatted response array
     */
    public function getResponse(): array
    {
        $response = new Response();
        $response
            ->setStatus(200)
            ->setData(array_merge(
                ['message' => 'Completed successfully'], 
                $this->data_response
            ))
            ->build();

        return $response->get();
    }

    /**
     * Set additional data for API response
     * 
     * Allows adding custom data to the response that will be merged
     * with the standard success message. Useful for returning
     * operation results, generated IDs, or other response data.
     * 
     * @param array $value Additional data to include in response
     * @return self For method chaining
     */
    protected function setDataResponse(array $value): self
    {
        $this->data_response = $value;
        return $this;
    }
}