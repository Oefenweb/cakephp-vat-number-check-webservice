<?php
namespace VatNumberCheck\Model\Datasource;

use Cake\Log\Log;

/**
 * SOAP DataSource.
 *
 */
class Soap
{
    /**
     * SoapClient instance.
     *
     * @var \SoapClient|null
     */
    protected $client = null;

    /**
     * URI of the WSDL file or NULL if working in non-WSDL mode.
     *
     * @var null|string
     */
    protected $wsdl = null;

    /**
     * Options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Connection status.
     *
     * @var bool
     */
    protected $connected = false;

    /**
     * Setter for the `wsdl` property.
     *
     * @param string $wsdl URI of the WSDL file or NULL if working in non-WSDL mode.
     * @return bool
     */
    public function setWsdl(string $wsdl): bool
    {
        $this->wsdl = $wsdl;

        return true;
    }

    /**
     * Setter for the `options` property.
     *
     * @param array $options Options
     * @return bool
     */
    public function setOptions(array $options = []): bool
    {
        $this->options = $options;

        return true;
    }

    /**
     * Get SoapClient instance.
     *
     * @return \SoapClient|null
     */
    public function getClient()
    {
        return $this->client;
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
                $this->client = new \SoapClient($this->wsdl, $this->options);
                $this->connected = (bool)$this->client;

                return $this->connected;
            } catch (\SoapFault $e) {
                Log::error($e);
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
     * @param array $data A list with parameters to pass
     * @param array $headers A list of headers to set
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
                    ->__setSoapHeaders(new \SoapHeader($header['namespace'], $header['name'], $header['data']));
            }

            return $this->client->__soapCall($method, $data);
        } catch (\SoapFault $e) {
            Log::error($e);

            return false;
        }
    }

    /**
     * Call methods from the SoapClient class.
     *
     * @param string $method A method name
     * @param array $params Method arguments
     * @return mixed Returns the result on success, false on failure
     */
    public function __call(string $method, array $params = [])
    {
        if (is_null($this->client)) {
            return false;
        }

        $callable = [$this->client, sprintf('__%s', $method)];
        if (!is_callable($callable)) {
            return false;
        }

        return call_user_func_array($callable, $params);
    }
}
