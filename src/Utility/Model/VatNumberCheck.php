<?php
namespace VatNumberCheck\Utility\Model;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use VatNumberCheck\Model\Datasource\Soap;

/**
 * VatNumberCheck Model.
 *
 */
class VatNumberCheck
{

    /**
     * SOAP connection
     *
     * @var Soap
     */
    protected $soapDataSource;

    /**
     * check VAT SOAP action
     *
     * @var string
     */
    const CHECK_VAT_SOAP_ACTION = 'checkVat';

    /**
     * Initializes the SOAP connection.
     *
     * @throws InternalErrorException
     */
    public function __construct() {
        $config = Configure::readOrFail('Plugins.VatNumberCheck');
        if (!$this->getSoapDataSource($config)->connect()) {
            throw new InternalErrorException('Connection to web service could not be established.');
        }
    }

    /**
     * Normalizes a VAT number.
     *
     * @param string $vatNumber A VAT number
     * @return string A (normalized) VAT number
     */
    public function normalize(string $vatNumber): string
    {
        return (string)preg_replace('/[^A-Z0-9]/', '', strtoupper($vatNumber));
    }

    /**
     * Checks a given VAT number.
     *
     * @param string $prefixedVatNumber A VAT number
     * @return bool Valid or not
     * @throws InternalErrorException when it is not possible to check the data
     */
    public function check(string $prefixedVatNumber): bool
    {
        $countryCode = substr($prefixedVatNumber, 0, 2);
        $vatNumber = substr($prefixedVatNumber, 2);
        $data = compact('countryCode', 'vatNumber');

        $result = $this->soapDataSource->query(static::CHECK_VAT_SOAP_ACTION, $data);

        return $result->valid ?? false;
    }

    /**
     * Returns an initialized Soap data source.
     *
     * @param array<string, mixed> $config An array of configuration
     * @return Soap The soap datasource
     */
    protected function getSoapDataSource(array $config = []): Soap
    {
        if (!isset($this->soapDataSource)) {
            $this->soapDataSource = new Soap($config);
        }

        return $this->soapDataSource;
    }
}
