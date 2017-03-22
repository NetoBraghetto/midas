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
		// $a = ['a' => 1, 'b' => 1, 'c' => 1, ];
		// $b = ['a' => 1, 'b' => 1, 'c' => 1, 'd' => 2];
		// dd(count(array_diff_key($a, $b)));
		// dd($this->payer);
		// $payer = new MercadoPago(self::$config[]);
		// dd($this->payer);
		$this->payer->fill([
		    'order' => [
		        'id' => rand(1, 500000),
		        'subtotal' => 1182.94,
		        'extra' => -118.29,
		        'total' => 1064.65,
		        'installments' => 3,
		        'no_interest_installments' => 2,
		        'shipping_type' => 1,
		        'shipping_price' => 23.57,
		        'currency' => 'BRL',
		    ],
		    'payment' => [
		        'group' => 1,
		        'method' => 101,
		        'cc_name' => 'CC HOLDER NAME',
		        'cc_cpf' => '02688315641',
		        'cc_number' => '4111111111111111',
		        'cc_day_of_birth' => '29',
		        'cc_month_of_birth' => '11',
		        'cc_year_of_birth' => '1988',
		        'cc_phone_area_code' => '16',
		        'cc_phone_number' => '36274820',
		        'cc_valid_month' => '12',
		        'cc_valid_year' => '2030',
		        'cc_cvv' => '123',
		    ],
		    'customer' => [
		        'id' => 1,
		        'name' => 'Antonio',
		        'last_name' => 'Almeida de Souza',
		        'cpf' => '02688315641',
		        'email' => 'antonio.almeida@gmail.com',
		        'phones' => [
		            ['area_code' => '16', 'number' => '36274820'],
		        ],
		        'day_of_birth' => '29',
		        'month_of_birth' => '11',
		        'year_of_birth' => '1988',
		        'created_at' => 'YYYY-MM-DD HH:mm:ss',
		    ],
		    'billing_address' => [
		        'street' => 'Rua Casequis Imirantes',
		        'number' => '1375',
		        'complement' => null,
		        'block' => 'Jardim Pinheiros',
		        'zip_code' => '14092-182',
		        'city' => 'Ribeirão Preto',
		        'uf' => 'SP',
		        'country' => 'BRA',
		    ],
		    'shipping_address' => [
		        'street' => 'Rua Casequis Imirantes',
		        'number' => '1375',
		        'complement' => null,
		        'block' => 'Jardim Pinheiros',
		        'zip_code' => '14092-182',
		        'city' => 'Ribeirão Preto',
		        'uf' => 'SP',
		        'country' => 'BRA'
		    ],
		    'items' => 2,
		    // 'items' => [
		    //     [
		    //         'id' => 1,
		    //         'name' => 'Lumia 925 - preto',
		    //         'description' => 'Lumia 925 - preto - Long item description.',
		    //         'image_url' => 'http://placehold.it/300x300',
		    //         'quantity' => 1,
		    //         'price' => 1137.27,
		    //         'width' => 85,
		    //         'height' => 50,
		    //         'length' => 123,
		    //         'weight' => 1000,
		    //     ],
		    //     [
		    //         'id' => 2,
		    //         // 'name' => 'Capra protetora para celular - transparente',
		    //         'description' => 'Capra protetora para celular - transparente - Long item description.',
		    //         'image_url' => 'http://placehold.it/300x300',
		    //         'quantity' => 1,
		    //         'price' => 22.10,
		    //         'width' => 85,
		    //         'height' => 50,
		    //         'length' => 123,
		    //         'weight' => 237,
		    //     ]
		    // ],
		    'vendor' => [ // Array with extra fields to merge in the request
		        // ...
		    ]
		]);
	}
}
