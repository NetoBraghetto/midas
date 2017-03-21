<?php
namespace Braghetto\Midas\Test;

use Braghetto\Midas\Payers\MercadoPago;

class MercadoPagoTest extends AbstractTestCase
{
	public static $config;

	private $payer;

	public static function setUpBeforeClass()
    {
        self::$config = require(__DIR__ . '/../config/config_test.php');
        self::$config = self::$config['mercado_pago'];
    }

    protected function setUp()
    {
        $this->payer = new MercadoPago(self::$config['client_id'], self::$config['client_secret']);
    }

	public function testAuthentication()
	{
		$a = ['a' => 1, 'b' => 1, 'c' => 1, ];
		$b = ['a' => 1, 'b' => 1, 'c' => 1, 'd' => 2];
		dd(count(array_diff_key($a, $b)));
		dd($this->payer);
		// $payer = new MercadoPago('');
	}
}
