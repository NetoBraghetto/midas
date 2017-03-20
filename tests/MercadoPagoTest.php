<?php
namespace Braghetto\Midas\Test;

use Braghetto\Midas\Payers\MercadoPago;

class MercadoPagoTest extends AbstractTestCase
{
	public $config;

	public static function setUpBeforeClass()
    {
        $this->config = require(__DIR__ . '/../config/config_test.php');
    }

	public function testAuthentication()
	{
		dd($this->config);
		// $payer = new MercadoPago('');
	}
}
