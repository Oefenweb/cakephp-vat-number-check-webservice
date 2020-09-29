<?php
namespace VatNumberCheck\Utility\Model;

use Cake\Core\Configure;
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
     */
    public function __construct()
    {
        $config = Configure::readOrFail('Plugins.VatNumberCheck');
        if (!isset($this->soapDataSource)) {
            $this->soapDataSource = new Soap($config);
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
     */
    public function check(string $prefixedVatNumber): bool
    {
        $countryCode = substr($prefixedVatNumber, 0, 2);
        $vatNumber = substr($prefixedVatNumber, 2);
        $data = compact('countryCode', 'vatNumber');

        $result = $this->soapDataSource->query(static::CHECK_VAT_SOAP_ACTION, $data);

        return $result->valid ?? false;
    }
}
