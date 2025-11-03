<?php

declare(strict_types=1);

namespace models\response;

use yii\data\Pagination;
use yii\helpers\Url;

class Response
{
    public const RESPONSE_FORBIDDEN = 'forbidden';
    public const RESPONSE_NOT_EXISTS = 'not_exists';

    public mixed $data = null;
    public ?array $pagination = null;
    public ?int $status = null;
    public ?int $count = null;
    public mixed $extra = null;
    public array $params = [];

    protected array $result = [];

    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setPagination(Pagination $pages): self
    {
        $this->pagination['links']['self'] = Url::current(['page' => $pages->getPage() + 1]);
        $this->pagination['links']['next'] = $pages->getPage() + 1 < $pages->getPageCount() 
            ? Url::current(['page' => $pages->getPage() + 2]) 
            : '';
        $this->pagination['links']['prev'] = $pages->getPage() > 0 
            ? Url::current(['page' => $pages->getPage()]) 
            : '';
        $this->pagination['links']['first'] = Url::current(['page' => 1]);
        $this->pagination['links']['last'] = Url::current(['page' => $pages->getPageCount()]);

        $this->pagination['meta']['totalCount'] = $pages->totalCount;
        $this->pagination['meta']['pageCount'] = $pages->getPageCount();
        $this->pagination['meta']['currentPage'] = $pages->getPage();
        $this->pagination['meta']['perPage'] = $pages->getPageSize();

        return $this;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function setExtra(mixed $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    public function build(): self
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

    public function get(): array
    {
        return $this->result;
    }

    public function addParams(string $key, mixed $param): self
    {
        $this->params[$key] = $param;

        return $this;
    }
}