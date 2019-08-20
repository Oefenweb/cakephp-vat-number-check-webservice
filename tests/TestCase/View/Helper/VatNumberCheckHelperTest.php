<?php
namespace VatNumberCheck\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use VatNumberCheck\View\Helper\VatNumberCheckHelper;

/**
 * VatNumberCheckHelper Test Case.
 *
 * @property \VatNumberCheck\Test\TestCase\View\Helper $VatNumberCheck
 */
 class VatNumberCheckHelperTest extends TestCase
{

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
		$this->assertContains('name="Foo[bar]"', $actual);
		$this->assertContains('id="foo-bar"', $actual);

		// Test class property -> only + append
		$options = [];
		$actual = $this->VatNumberCheck->input($fieldName, $options);
		$this->assertContains('class="vat-number-check"', $actual);

		$options = ['class' => 'foo-bar'];
		$actual = $this->VatNumberCheck->input($fieldName, $options);
		$this->assertContains('class="foo-bar vat-number-check"', $actual);

		// Test input type
		$options = ['type' => 'radio'];
		$actual = $this->VatNumberCheck->input($fieldName, $options);
		$this->assertContains('type="text"', $actual);
	}

}
