<?php
namespace Pixelindustries\JsonApi\Contracts\Support\Request;

interface RequestParserInterface
{

    /**
     * Returns full filter data.
     *
     * @return array
     */
    public function getFilter();

    /**
     * Returns a specific key's value from the filter.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function getFilterValue($key, $default = null);

    /**
     * Returns full page data.
     *
     * @return array
     */
    public function getPageData();

    /**
     * @return int
     */
    public function getPageNumber();

    /**
     * @return int
     */
    public function getPageSize();

}
