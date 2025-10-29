<?php
namespace models;

use models\interfaces\Observer;

abstract class ABaseObject
{
    protected $errors;
    protected $result = false;
    protected $observers;
    protected $model_ar;
    
    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    protected function afterBuild()
    {
        $this->notifyObservers();
        $this->deleteCache();
    }

    /**
     * {@inheritdoc}
     */
    public function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function notifyObservers()
    {
        if (!empty($this->observers)) {
            $objectObs = method_exists($this, 'getObjectObserver')?$this->getObjectObserver():null;
            
            if (!$objectObs && $this->model_ar) {
                $objectObs = $this->model_ar;
            }

            if (!$objectObs) return;

            foreach ($this->observers as $observer) {
                $observer->notify($objectObs);
            }
        }    
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getModelAr()
    {
        return $this->model_ar;
    }
}
