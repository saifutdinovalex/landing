<?php
namespace models;

use Yii;
use yii\base\Model;

/**
 * every model has to extend this abstract class
 */
abstract class AbstractModel extends Model
{
    /** implements method */
    public function execute()
    {
        return true;
    }
}
