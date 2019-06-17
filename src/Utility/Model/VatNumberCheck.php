<?php
namespace VatNumberCheck\Utility\Model;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use VatNumberCheck\Soap\Soap;
use VatNumberCheck\Utility\Model\App;
/**
 * VatNumberCheck Model.
 *
 */
class VatNumberCheck extends App
{
/**
 * The name of the soap connection that this Model uses.
 *
 * @var string
 */
	public $useDbConfig = 'vatNumberCheckWebservice';

/**
 * Use table.
 *
 * @var mixed False or table name
 */
	public $useTable = false;

/**
 * The (translation) domain to be used for extracted validation messages in models.
 *
 * @var string
 */
	public $validationDomain = 'vat_number_check';

/**
 * Normalizes a VAT number.
 *
 * @param string $vatNumber A VAT number
 * @return string A (normalized) VAT number
 */
	public function normalize(string $vatNumber) : string {
		return preg_replace('/[^A-Z0-9]/', '', strtoupper($vatNumber));
	}

/**
 * Checks a given VAT number.
 *
 * @param string $vatNumber A VAT number
 * @return bool Valid or not
 * @throws InternalErrorException
 */
	public function check(string $vatNumber) : bool {
		$memberStateCode = substr($vatNumber, 0, 2);
		$number = substr($vatNumber, 2);

		$params = ['countryCode' => $memberStateCode, 'vatNumber' => $number];

		$result = $this->query('checkVat', $params);

		return $result->valid ?? false;
	}
}