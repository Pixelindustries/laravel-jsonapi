<?php
namespace Pixelindustries\JsonApi\Support\Request;

use Illuminate\Http\Request;
use Pixelindustries\JsonApi\Contracts\Support\Request\RequestParserInterface;

class RequestParser implements RequestParserInterface
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * Whether the request has been analyzed yet.
     *
     * @var bool
     */
    protected $analyzed = false;

    /**
     * Filter JSON-API data.
     *
     * @var array
     */
    protected $filter = [];

    /**
     * Page JSON-API data.
     *
     * @var array
     */
    protected $page = [];


    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Returns full filter data.
     *
     * @return array
     */
    public function getFilter()
    {
        $this->analyze();

        return $this->filter;
    }

    /**
     * Returns a specific key's value from the filter.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getFilterValue($key, $default = null)
    {
        return array_get($this->getFilter(), $key, $default);
    }

    /**
     * Returns full page data.
     *
     * @return array
     */
    public function getPageData()
    {
        $this->analyze();

        return $this->page;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return (int) array_get($this->page, 'number', 1);
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return (int) array_get($this->page, 'size');
    }


    /**
     * Analyzes the request to retrieve JSON-API relevant data.
     *
     * @param bool $force
     */
    protected function analyze($force = false)
    {
        if ( ! $force && $this->analyzed) {
            return;
        }

        $this->analyzed = true;
    }

}
