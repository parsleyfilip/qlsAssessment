<?php

namespace App\Http\Controllers;

class StartPageController extends Controller
{
    public function showOrderForm()
    {
        // array met de data van de order die al gemaakt is (thx)
        // maakt het makkelijker voor het testen enzo
        $order = [
            'number' => '#958201',
            'billing_address' => [
                'companyname' => null,
                'name' => 'John Doe',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'address_line_2' => '',
                'zipcode' => '3316GD',
                'city' => 'Dordrecht',
                'country' => 'NL',
                'email' => 'email@example.com',
                'phone' => '0101234567',
            ],
            'delivery_address' => [
                'companyname' => '',
                'name' => 'John Doe',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'address_line_2' => '',
                'zipcode' => '3316GD',
                'city' => 'Dordrecht',
                'country' => 'NL',
            ],
            'order_lines' => [
                [
                    'amount_ordered' => 2,
                    'name' => 'Jeans - Black - 36',
                    'sku' => 69205,
                    'ean' =>  '8710552295268',
                ],
                [
                    'amount_ordered' => 1,
                    'name' => 'Sjaal - Rood Oranje',
                    'sku' => 25920,
                    'ean' =>  '3059943009097',
                ]
            ]
        ];
        // de controller retourneerd de de data door naar de view
        return view('start', compact('order'));
    }
}
