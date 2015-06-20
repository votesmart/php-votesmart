<?php namespace VoteSmart;

/**
 * VoteSmart API interfacing class
 *
 * This class works by initializing the object. Once initialized you can call the
 * query() method to make calls to VoteSmart. You can get the output by inspecting
 * the response values of the getXml() and getXmlObj() methods.
 * 
 * Copyright 2008 Project Vote Smart
 * Distributed under the BSD License
 * 
 * http://www.opensource.org/licenses/bsd-license.php
 * 
 * Special thanks to Adam Friedman for the idea and code
 * contribution for the slimmed down version of this lib.
 *
 * @author VoteSmart.org
 * @author Adam Friedman
 * @author Derek Rosenzweig <derek.rosenzweig@gmail.com>
 * @class Api
 * @name Api
 */
class Api
{
    //--------------------------------------------------------------------------
    //
    //  Static Variables
    //
    //--------------------------------------------------------------------------

    /**
     * The API endpoint where requests are sent.
     *
     * @static
     * @var string
     */
    public static $API_SERVER = "http://api.votesmart.org";

    /**
     * Possible return formats from API endpoint.
     *
     * @static
     * @var array
     */
    public static $OUTPUT_TYPES = [
        'XML',
        'JSON'
    ];

    //--------------------------------------------------------------------------
    //
    //  Variables and get/set functions
    //
    //--------------------------------------------------------------------------

    /**
     * The full query URL.
     *
     * @var string
     */
    protected $iface;

    /**
     * function getIface
     *
     * Return string of URL queried
     *
     * @return string
     */
    public function getIface()
    {
        return $this->iface;
    }

    /**
     * Raw XML
     *
     * @var string
     */
    protected $xml;

    /**
     * function getXml
     *
     * Return raw XML string
     *
     * @return string
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * SimpleXML object
     *
     * @var \SimpleXMLElement
     */
    protected $xmlObj;

    /**
     * Return SimpleXMLElement object
     *
     * @return \SimpleXMLElement
     */
    public function getXmlObj()
    {
        return $this->xmlObj;
    }

    /**
     * Raw JSON
     *
     * @var string
     */
    protected $json;

    /**
     * Return raw JSON string
     *
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * Decoded JSON data
     *
     * @var array
     */
    protected $jsonObj;

    /**
     * Return decoded JSON array.
     *
     * @return array
     */
    public function getJsonObj()
    {
        return $this->jsonObj;
    }

    /**
     * The array key in $_ENV where you are storing your VoteSmart API token.
     *
     * @var string
     */
    protected $envKey = null;

    /**
     * Sets the array key in $_ENV where you are storing your VoteSmart API token, and updates
     * the apiKey stored in this object to point to the new location in $_ENV.
     *
     * @throws \InvalidArgumentException iff the output type isn't a string
     * @throws \Exception iff the API token can't be found in $_ENV
     *
     * @param string $envKey The array key in $_ENV where the VoteSmart token exists.
     */
    public function setEnvKey($envKey)
    {
        if (! is_string($envKey)) {
            throw new \InvalidArgumentException(
                "The environment key '{$envKey}' is expecting a string"
            );
        }
        if (empty($_ENV[$envKey])) {
            throw new \Exception(
                "VoteSmart requires an API authentication token. Place it in your application's `\$_ENV['".$envKey."']` variable."
            );
        }
        $this->envKey = $envKey;
        $apiToken = $_ENV[$this->envKey];
        $this->setApiToken($apiToken);
    }

    /**
     * Authentication token provided to you by VoteSmart. Place it in your application's
     * `$_ENV['VOTESMART_API_KEY']` variable so that it is globally available. For example,
     * in the Laravel framework, you would put this in your .env.php file.
     *
     * If you've stored your key in another $_ENV key already, you can pass in the key where
     * the API token can be found in the constructor.
     *
     * @var string
     */
    protected $apiToken = null;

    /**
     * Sets the API token.
     *
     * @param string $apiToken Sets the API key. Required.
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * The type of output to request from VoteSmart.
     *
     * @var string
     */
    protected $outputType = null;

    /**
     * Set the expected output type.
     *
     * @throws \InvalidArgumentException iff the output type isn't a string
     * @throws \Exception iff the output type isn't supported
     *
     * @param string $outputType The expected output type from VoteSmart.
     */
    public function setOutputType($outputType)
    {
        if (! is_string($outputType)) {
            throw new \InvalidArgumentException(
                "The output type '{$outputType}' is unsupported."
            );
        }
        if (! in_array($outputType, static::$OUTPUT_TYPES)) {
            throw new \Exception(
                "The output type '{$outputType}' is unsupported."
            );
        }
        $this->outputType = $outputType;
    }

    //--------------------------------------------------------------------------
    //
    //  Methods
    //
    //--------------------------------------------------------------------------

    /* Private Methods */

    /* Protected Methods */

    /**
     * Use SimpleXML to drop the whole XML output into an object we can later interact with easily.
     * Stores both the XML and the constructed SimpleXMLElement object.
     *
     * @param string $xml
     */
    protected function parseXml($xml)
    {
        $this->xml = $xml;
        $this->xmlObj = new \SimpleXMLElement($this->xml, LIBXML_NOCDATA);
    }

    /**
     * Take in a JSON string and decode it. Store both the JSON and the decoded array.
     *
     * @param string $json
     */
    protected function parseJson($json)
    {
        $this->json = $json;
        $this->jsonObj = json_decode($this->json);
    }

    /* Public Methods */

    /**
     * Initialize the VoteSmart object and ready it to make queries.
     *
     * @param string $outputType optional The type of output to request from VoteSmart. Default is 'XML'.
     * @param string $envKey optional Key of your Authentication key stored in $_ENV var. Default is 'VOTESMART_API_KEY'.
     */
    public function __construct($outputType = 'XML', $envKey = 'VOTESMART_API_KEY')
    {
        $this->setOutputType($outputType);
        $this->setEnvKey($envKey);
    }

    /**
     * Query API backend and parse the response, either as JSON or XML depending on the instance's outputType
     * attribute. Returns false if it can't get the contents, a SimpleXMLElement if the output type is XML,
     * or an array if the output type is JSON.
     *
     * @param string $method required 'CandidateBio.getBio'
     * @param array $args optional Array('candidateId' => '54321')
     *
     * @return false|\SimpleXMLElement|array
     */
    public function query($method, $args = Array())
    {
        $terms = "";

        if (! empty($args)) {
            foreach ($args as $n => $v) {
                $terms .= "&{$n}={$v}";
            }
        }
        $this->iface = static::$API_SERVER . "/" . $method . "?key=" . $this->apiToken . "&o=" . $this->outputType  . $terms;

        $response = file_get_contents($this->iface);
        if (! $response) {
            return false;
        }

        if ($this->outputType == 'JSON') {
            $this->parseJson($response);
            return $this->responseArr;
        }

        // Parse as XML by default.
        $this->parseXml($response);
        return $this->xmlObj;
    }
}
