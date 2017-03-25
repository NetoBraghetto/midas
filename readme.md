Midas
===================

[TOC]


Fields
-------------

```php
$data = [
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
    'items' => [
        [
            'id' => 1,
            'name' => 'Lumia 925 - preto',
            'description' => 'Lumia 925 - preto - Long item description.',
            'image_url' => 'http://placehold.it/300x300',
            'quantity' => 1,
            'price' => 1137.27,
            'width' => 85,
            'height' => 50,
            'length' => 123,
            'weight' => 1000,
        ],
        [
            'id' => 2,
            'name' => 'Capra protetora para celular - transparente',
            'description' => 'Capra protetora para celular - transparente - Long item description.',
            'image_url' => 'http://placehold.it/300x300',
            'quantity' => 1,
            'price' => 22.10,
            'width' => 85,
            'height' => 50,
            'length' => 123,
            'weight' => 237,
        ]
    ],
    'vendor' => [ // Array with extra fields to merge in the request
        // ...
    ]
]
```

Codes
-------------

###Status
| code | Description |
|---|---|
| 1 | Approved |
| 2 | Waiting for payment |
| 3 | Analyzing |
| 4 | Rejected |
| 5 | Canceled |

###Banks

| code | Description |
|---|---|
| 1 | Banco do Brasil |
| 41 | Banrisul |
| 237 | Bradesco |
| 341 | Itaú |
| 399 | HSBC |

###Payment group

| code | Description |
|---|---|
| 1 | Credit card |
| 2 | Boleto |
| 3 | Debit |

###Payment method

| code | Description |
|---|---|
| 101 | Cartão de crédito Visa |
| 102 | Cartão de crédito MasterCard |
| 103 | Cartão de crédito American Express |
| 104 | Cartão de crédito Diners |
| 105 | Cartão de crédito Hipercard |
| 106 | Cartão de crédito Aura |
| 107 | Cartão de crédito Elo |
| 108 | Cartão de crédito PLENOCard |
| 109 | Cartão de crédito PersonalCard |
| 110 | Cartão de crédito JCB |
| 111 | Cartão de crédito Discover |
| 112 | Cartão de crédito BrasilCard |
| 113 | Cartão de crédito FORTBRASIL |
| 114 | Cartão de crédito CARDBAN |
| 115 | Cartão de crédito VALECARD |
| 116 | Cartão de crédito Cabal |
| 117 | Cartão de crédito Mais! |
| 118 | Cartão de crédito Avista |
| 119 | Cartão de crédito GRANDCARD |
| 201 | Boleto Bradesco |
| 202 | Boleto Santander |
| 303 | Débito online Bradesco |
| 304 | Débito online Itaú |
| 305 | Débito online Unibanco |
| 306 | Débito online Banco do Brasil |
| 307 | Débito online Banco Real |
| 308 | Débito online Banrisul |
| 309 | Débito online HSBC |
| 401 | Saldo PagSeguro |
| 501 | Oi Paggo |
| 601 | Depósito em conta - Banco do Brasil |
