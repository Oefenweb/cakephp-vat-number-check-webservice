<?php
App::uses('VatNumberCheck', 'VatNumberCheck.Model');

/**
 * VatNumberCheck Test Case
 *
 * @property VatNumberCheck.VatNumberCheck $VatNumberCheck
 */
class VatNumberCheckTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [];

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->VatNumberCheck = ClassRegistry::init('VatNumberCheck.VatNumberCheck', true);
		$this->VatNumberCheck->setDataSource('vatNumberCheckWebservice');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->VatNumberCheck);

		parent::tearDown();
	}

/**
 * Tests `normalize`.
 *
 * @param string $vatNumber
 * @param string $expected
 * @return void
 * @dataProvider normalizeProvider
 */
	public function testNormalize(string $vatNumber, string $expected) {
		$actual = $this->VatNumberCheck->normalize($vatNumber);
		$this->assertSame($expected, $actual);
	}

/**
 * Data provider for `normalize`.
 *
 * @return array
 */
	public function normalizeProvider() : array {
		return [
			// $vatNumber, $expected

			// Correct
			['NL820345672B01', 'NL820345672B01'],
			// To upper case
			['NL820345672b01', 'NL820345672B01'],
			// Removal of non-alphanumeric
			['NL820345672 B01', 'NL820345672B01'],
			['NL820345672!B01',  'NL820345672B01'],
		];
	}

/**
 * Tests `check`.
 *
 * @param string $vatNumber
 * @param string $expected
 * @return void
 * @dataProvider checkProvider
 */
	public function testCheck(string $vatNumber, bool $expected) {
		$actual = $this->VatNumberCheck->check($vatNumber);
		$this->assertSame($expected, $actual);
	}

/**
 * Data provider for `check`.
 *
 * @return array
 */
	public function checkProvider() : array {
		return [
			// $vatNumber, $expected

			// Correct
			['NL820345672B01', true],
			['BE0475899519', true],
			// Incorrect vat
			['NL820345672B02', false],
			// Empty vat
			['', false],
		];
	}
}
