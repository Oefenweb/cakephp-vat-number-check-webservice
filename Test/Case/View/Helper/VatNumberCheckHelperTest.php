<?php
App::uses('View', 'View');
App::uses('Helper', 'View');
App::uses('VatNumberCheckHelper', 'VatNumberCheck.View/Helper');

/**
 * VatNumberCheckHelper Test Case
 *
 * @property VatNumberCheck.VatNumberCheckHelper $VatNumberCheck
 */
class VatNumberCheckHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$View = new View();
		$this->VatNumberCheck = new VatNumberCheckHelper($View);
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
 * testInput method
 *
 * @return void
 */
	public function testInput() {
		$fieldName = 'Foo.bar';
		$actual = $this->VatNumberCheck->input($fieldName);

		// Test name and id properties
		$this->assertPattern('/name\=\"data\[Foo\]\[bar\]\"/', $actual);
		$this->assertPattern('/id\=\"FooBar\"/', $actual);

		// Test class property -> only + append
		$options = [];
		$actual = $this->VatNumberCheck->input($fieldName, $options);
		$this->assertPattern('/class\=\"vat-number-check\"/', $actual);

		$options = ['class' => 'foo-bar'];
		$actual = $this->VatNumberCheck->input($fieldName, $options);
		$this->assertPattern('/class\=\"foo-bar vat-number-check\"/', $actual);

		// Test input type
		$options = ['type' => 'radio'];
		$actual = $this->VatNumberCheck->input($fieldName, $options);
		$this->assertPattern('/type\=\"text\"/', $actual);
	}

}
