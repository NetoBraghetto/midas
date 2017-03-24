<?php
namespace Braghetto\Midas\Payers;

use Braghetto\Midas\Interfaces\Payable;
use MP;

/**
* MercadoPago
*/
class MercadoPago extends AbstractPayer implements Payable
{
    private $client;

    private $payment_methods = [
        '101' => 'visa'
    ];

    // protected $attributes;

    protected $parsedOrder;

    // protected $items;

    // protected $parsedItems;

    // protected $customer;

    // protected $parsedCustomer;

    // protected $address;

    // protected $parsedAddress;

    protected $validationFields = [
        'order' => [
            'id',
            'total',
            'installments',
        ],
        'payment' => [
            'method',
        ],
        'items' => [
            [
                'id',
                'name',
                'image_url',
                'description',
                'quantity',
                'price',
            ]
        ],
        'shipping_address' => [
            'street',
            'number',
            'zip_code',
        ],
        'customer' => [
            'name',
            'last_name',
            'email',
            'phones' => [
                ['area_code', 'number'],
            ],
            'created_at',
        ],
    ];

    public function __construct($client_id, $client_secret = null)
    {
        $this->client = new MP($client_id, $client_secret);
    }

    public function fill(array $data)
    {
        if (!$this->validate($this->validationFields, $data)) {
            return false;
        }
        $this->parsedOrder = [
            'transaction_amount' => $data['order']['total'],
            'installments' => $data['order']['installments'],
            'payment_method_id' => $this->payment_methods[$data['payment']['method']],
            'payer' => [
                'email' => $data['customer']['email'],
            ],
            'external_reference' => $data['order']['id'],
            'additional_info' => [
                'items' => $this->parseItems($data['items']),
                'payer' => [
                    'first_name' => $data['customer']['name'],
                    'last_name' => $data['customer']['last_name'],
                    'registration_date' => $data['customer']['created_at'],
                    'phone' => [
                        'area_code' => $data['customer']['phones'][0]['area_code'],
                        'number' => $data['customer']['phones'][0]['number'],
                    ],
                    'address' => [
                        'street_name' => $data['shipping_address']['street'],
                        'street_number' => $data['shipping_address']['number'],
                        'zip_code' => $data['shipping_address']['zip_code'],
                    ],
                ],
                'shipments' => [
                    'receiver_address' => [
                        'street_name' => $data['shipping_address']['street'],
                        'street_number' => $data['shipping_address']['number'],
                        'zip_code' => $data['shipping_address']['zip_code'],
                    ],
                ],
            ],
        ];

        if (!empty($data['vendor'])) {
            $this->parsedOrder = array_merge($this->parsedOrder, $data['vendor']);
        }
    }

    public function pay()
    {
        $payment = $this->client->post('/v1/payments', $this->parsedOrder);
        foreach ($payment['reponse']['fee_details'] as $fee) {
            if ($fee['type'] == 'mercadopago_fee') {
                $vendor_fee = $fee['amount']
            }
        }
        if ($payment['status'] == 201) {
            return [
                'vendor_id' => $payment['reponse']['id'],
                // 'status' => $payment['reponse']['status'], // De-por
                'order_total' => $payment['reponse']['transaction_details']['total_paid_amount'],
                'received_amount' => $payment['reponse']['transaction_details']['net_received_amount'],
                'total_paid' => $payment['reponse']['transaction_details']['total_paid_amount'],
                'installments' => $payment['reponse']['installments'],
                'installment_value' => $payment['reponse']['transaction_details']['installment_amount'],
                'vendor_fee' => $vendor_fee,

                'http_status' => $payment['status'],
                'response' => $payment['reponse']
            ];
        }
        return false;
    }

    protected function parseItems(array $items)
    {
        $parsed = [];
        foreach ($items as $item) {
            $parsed[] = [
                'id' => $item['id'],
                'title' => $item['name'],
                'picture_url' => $item['image_url'],
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ];
        }
        return $parsed;
    }
}
