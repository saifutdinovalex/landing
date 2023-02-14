<?php
namespace landing;

class BaseConfig
{
    /**
     * возвращаем стоимость
     * @param string тариф
     * @return int
     */
    public static function getPrice($name)
    {

        $key = 'key_landing_umarov_tariff_price';
        $result = \Yii::$app->cache->getOrSet($key, function() {
            return \Yii::$app->db->createCommand('SELECT * FROM price')->queryAll();
        });

        if ($result) {
            foreach ($result as $key => $value) {
                if ($name == $value['name']) {
                    return $value['value'];
                }
            }
        }

        return 0;
    }
}