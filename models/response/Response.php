<?php

namespace models\response;

use yii\data\Pagination;
use yii\helpers\Url;

class Response
{
    const RESPONSE_FORBIDDEN = 'forbidden';
    const RESPONSE_NOT_EXISTS = 'not_exists';

    public $data = null;
    public $pagination = null;
    public $status = null;
    public $count = null;
    public $extra = null;
    public $params = [];

    protected $result= [];

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param Pagination $pages
     * @return $this
     */
    public function setPagination(Pagination $pages)
    {
        $this->pagination['links']['self'] = Url::current(['page' => $pages->getPage() + 1]);
        $this->pagination['links']['next'] = $pages->getPage() + 1 < $pages->getPageCount() ? Url::current(['page' => $pages->getPage() + 2]) : '';
        $this->pagination['links']['prev'] = $pages->getPage() > 0 ? Url::current(['page' => $pages->getPage()]) : '';
        $this->pagination['links']['first'] = Url::current(['page' => 1]);
        $this->pagination['links']['last'] = Url::current(['page' => $pages->getPageCount()]);

        $this->pagination['meta']['totalCount'] = $pages->totalCount;
        $this->pagination['meta']['pageCount'] = $pages->getPageCount();
        $this->pagination['meta']['currentPage'] = $pages->getPage();
        $this->pagination['meta']['perPage'] = $pages->getPageSize();

        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param $count
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @param null $extra
     * @return BaseResponse
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
        return $this;
    }

    /**
     * @return $this
     */
    public function build()
    {
        if ($this->status !== null) {
            $this->result['status'] = $this->status;
        }

        if ($this->data !== null) {
            $this->result['data'] = $this->data;
        }

        if ($this->count !== null) {
            $this->result['count'] = $this->count;
        }

        if ($this->pagination !== null) {
            $this->result['pagination'] = $this->pagination;
        }

        if ($this->extra !== null) {
            $this->result['extra'] = $this->extra;
        }

        if (count($this->params) > 0) {
            foreach ($this->params as $key => $param) {
                $this->result[$key] = $param;
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->result;
    }

    /**
     * @param $key
     * @param $param
     * @return $this
     */
    public function addParams($key, $param)
    {
        $this->params[$key] = $param;
        return $this;
    }
}