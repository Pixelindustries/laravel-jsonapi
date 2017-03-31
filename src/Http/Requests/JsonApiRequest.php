<?php
namespace Pixelindustries\JsonApi\Http\Requests;

use Illuminate\Http\Request;
use Pixelindustries\JsonApi\Support\Request\RequestQueryParser;

class JsonApiRequest extends Request
{

    /**
     * @var RequestQueryParser
     */
    protected $jsonApiParser;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        parent::__construct();

        $this->jsonApiParser = new RequestQueryParser($this);
    }

    /**
     * @return RequestQueryParser
     */
    public function jsonApi()
    {
        return $this->jsonApiParser;
    }

    /**
     * Returns full filter data.
     *
     * @return array
     */
    public function jsonApiFilter()
    {
        return $this->jsonApiParser->getFilter();
    }

    /**
     * Returns a specific key's value from the filter.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function jsonApiFilterValue($key, $default = null)
    {
        return $this->jsonApiParser->getFilterValue($key, $default);
    }

    /**
     * Returns full page data.
     *
     * @return array
     */
    public function jsonApiPageData()
    {
        return $this->jsonApiParser->getPageData();
    }

    /**
     * @return int
     */
    public function jsonApiPageNumber()
    {
        return $this->jsonApiParser->getPageNumber();
    }

    /**
     * @return int
     */
    public function jsonApiPageSize()
    {
        return $this->jsonApiParser->getPageSize();
    }

}
