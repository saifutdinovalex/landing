<?php
namespace models;

use models\interfaces\Observer;

abstract class ABaseObject
{
    protected $errors;
    protected $result = false;
    protected $observers;
    protected $model_ar;
    
    public function getResult()
    {
        return $this->result;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function afterBuild()
    {
        $this->notifyObservers();
        $this->deleteCache();
    }

    public function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
        return $this;
    }

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

    public function getModelAr()
    {
        return $this->model_ar;
    }
}
