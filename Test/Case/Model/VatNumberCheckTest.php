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
 */
	public function testNormalize() {
		// Correct

		$vatNumber = 'NL820345672B01';
		$actual = $this->VatNumberCheck->normalize($vatNumber);
		$expected = 'NL820345672B01';

		$this->assertSame($expected, $actual);

		// To upper case

		$vatNumber = 'NL820345672b01';
		$actual = $this->VatNumberCheck->normalize($vatNumber);
		$expected = 'NL820345672B01';

		$this->assertIdentical($expected, $actual);

		// Removal of non-alphanumeric

		$vatNumber = 'NL820345672 B01';
		$actual = $this->VatNumberCheck->normalize($vatNumber);
		$expected = 'NL820345672B01';

		$this->assertIdentical($expected, $actual);

		$vatNumber = 'NL820345672!B01';
		$actual = $this->VatNumberCheck->normalize($vatNumber);
		$expected = 'NL820345672B01';

		$this->assertIdentical($expected, $actual);
	}

/**
 * Tests `check`.
 *
 * @return void
 */
	public function testCheck() {
		// Correct

		$vatNumber = 'NL820345672B01';
		$actual = $this->VatNumberCheck->check($vatNumber);

		$this->assertTrue($actual);

		$vatNumber = 'BE0207451227';
		$actual = $this->VatNumberCheck->check($vatNumber);

		$this->assertTrue($actual);

		// Incorrect vat

		$vatNumber = 'NL820345672B02';
		$actual = $this->VatNumberCheck->check($vatNumber);

		$this->assertFalse($actual);

		// Empty vat

		$vatNumber = '';
		$actual = $this->VatNumberCheck->check($vatNumber);

		$this->assertFalse($actual);
	}
}
