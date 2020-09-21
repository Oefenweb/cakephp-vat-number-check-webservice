<?php
namespace VatNumberCheck\Utility\Model;

use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use VatNumberCheck\Model\Datasource\Soap;

/**
 * VatNumberCheck Utility.
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
     * Service to check vat numbers.
     *
     */
    const CHECK_VAT_SERVICE = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

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

        $options = Configure::read('Plugins.VatNumberCheck.options');
        if (!$this->getSoapDataSource($options)->connect()) {
            throw new InternalErrorException('Unable to connect.');
        }

        $result = $this->soapDataSource->query(static::CHECK_VAT_SOAP_ACTION, $data);

        return $result->valid ?? false;
    }

    /**
     * Returns an initialized Soap data source.
     *
     * @param array<mixed> $options An array of options.
     * @return Soap the soap datasource
     */
    protected function getSoapDataSource(array $options = []): Soap
    {
        $wsdl = static::CHECK_VAT_SERVICE;
        if (!isset($this->soapDataSource)) {
            $this->soapDataSource = new Soap();
            $this->soapDataSource->setWsdl($wsdl);
            $this->soapDataSource->setOptions($options);
        }

        return $this->soapDataSource;
    }
}
