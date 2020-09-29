<?php
namespace VatNumberCheck\Model\Datasource;

use Cake\Log\Log;
use SoapClient;
use SoapFault;
use SoapHeader;

/**
 * SOAP DataSource.
 *
 * @method string getLastRequestHeaders()
 * @method string getLastRequest()
 * @method string getLastResponseHeaders()
 * @method string getLastResponse()
 */
class Soap
{
    /**
     * Configuration.
     *
     * @var array<string,mixed>
     */
    protected $config = [];

    /**
     * Options.
     *
     * @var array<string,mixed>
     */
    protected $options = [];

    /**
     * URI of the WSDL file or NULL if working in non-WSDL mode.
     *
     * @var null|string
     */
    protected $wsdl = null;

    /**
     * Configured value of default_socket_timeout.
     *
     * @var string
     */
    protected $defaultSocketTimeout;

    /**
     * Original value of default_socket_timeout.
     *
     * @var string
     */
    protected $originalDefaultSocketTimeout;

    /**
     * SoapClient instance.
     *
     * @var SoapClient|null
     */
    protected $client = null;

    /**
     * Connection status.
     *
     * @var bool
     */
    protected $connected = false;

    /**
     * Service to check vat numbers.
     *
     */
    const CHECK_VAT_SERVICE = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    /**
     * Constructor.
     *
     * @param array<string,mixed> $config Configuration.
     */
    public function __construct(array $config = [])
    {
        $this->originalDefaultSocketTimeout = ini_get('default_socket_timeout');

        $this->config = $config;

        $defaultOptions = [
            'exceptions' => true,
        ];
        $this->options = array_merge($defaultOptions, $config['options'] ?? []);

        $this->wsdl = $config['wsdl'] ?? static::CHECK_VAT_SERVICE;

        $this->defaultSocketTimeout = strval($config['default_socket_timeout'] ?? $this->originalDefaultSocketTimeout);

        debug($this->originalDefaultSocketTimeout);
        debug($this->defaultSocketTimeout);
    }

    /**
     * Connects to the server using the WSDL in the configuration.
     *
     * @return bool True on success, false on failure
     */
    public function connect(): bool
    {
        if (!empty($this->wsdl)) {
            try {
                ini_set('default_socket_timeout', $this->defaultSocketTimeout);

                $this->client = new SoapClient($this->wsdl, $this->options);
                $this->connected = (bool)$this->client;

                return $this->connected;
            } catch (SoapFault $e) {
                Log::error($e->getMessage());
                Log::error($e->getTraceAsString());
            } finally {
                ini_set('default_socket_timeout', $this->originalDefaultSocketTimeout);
            }
        }

        return false;
    }

    /**
     * Disconnects to the server.
     *
     * @return bool True
     */
    public function close(): bool
    {
        $this->client = null;
        $this->connected = false;

        return true;
    }

    /**
     * Query the server with the given method and parameters.
     *
     * @param string $method Name of method to call
     * @param array<mixed> $data A list with parameters to pass
     * @param array<int,array> $headers A list of headers to set
     * @return mixed Returns the result on success, false on failure
     */
    public function query(string $method, array $data = [], array $headers = [])
    {
        if (!$this->connected) {
            return false;
        }

        if (is_null($this->client)) {
            return false;
        }

        try {
            if (!empty($data)) {
                $data = [$data];
            }

            foreach ($headers as $header) {
                $this->client
                    ->__setSoapHeaders(new SoapHeader($header['namespace'], $header['name'], $header['data']));
            }

            ini_set('default_socket_timeout', $this->defaultSocketTimeout);

            return $this->client->__soapCall($method, $data);
        } catch (SoapFault $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        } finally {
            ini_set('default_socket_timeout', $this->originalDefaultSocketTimeout);
        }
    }
}
