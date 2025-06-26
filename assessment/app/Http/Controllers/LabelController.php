<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LabelController extends Controller
{
    public function downloadLabel($shipmentId)
    {
        $companyId = '9e606e6b-44a4-4a4e-a309-cc70ddd3a103';
        $user = 'frits@test.qlsnet.nl';
        $password = '4QJW9yh94PbTcpJGdKz6egwH';
    
        $url = "https://api.pakketdienstqls.nl/v2/companies/{$companyId}/shipments/{$shipmentId}/labels/pdf";
    
        $response = Http::withBasicAuth($user, $password)->get($url);
    
        // ik zeg het je wel eerlijk dit stukje is ook met AI gedaan er is hoogstwaarschijnlijk een beter manier om dit te doen
        if ($response->successful()) {
            \Log::info("Label ophalen gelukt voor shipmentId: {$shipmentId}");
            $json = $response->json();
            $pdf = base64_decode($json['data']);
            return response($pdf, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename=\"label.pdf\"');
        } else {
            \Log::error("Label ophalen mislukt voor shipmentId: {$shipmentId}. Fout: " . $response->body());
            abort(403, 'Label ophalen mislukt: ' . $response->body());
        }
    }


    public function create(Request $request)
    {
        // 1. haalt data uit het formulier
        $order = $request->input('order');

        $companyId = '9e606e6b-44a4-4a4e-a309-cc70ddd3a103';
        $brandId = 'e41c8d26-bdfd-4999-9086-e5939d67ae28';
        $productCombinationId = 3;

        // 2. zet al het data op in api formaat 
        $payload = [
            'product_combination_id' => $productCombinationId,
            'brand_id' => $brandId,
            'reference' => $order['number'],
            'receiver_contact' => [
                'name' => $order['delivery_address']['name'],
                'companyname' => $order['delivery_address']['companyname'],
                'street' => $order['delivery_address']['street'],
                'housenumber' => $order['delivery_address']['housenumber'],
                'address2' => $order['delivery_address']['address_line_2'],
                'postalcode' => $order['delivery_address']['zipcode'],
                'locality' => $order['delivery_address']['city'],
                'country' => $order['delivery_address']['country'],
                // email en telefoon is niet nodig voor delivery ding
            ],
            'shipment_products' => array_map(function($line) {
                return [
                    'name' => $line['name'],
                    'sku' => $line['sku'],
                    'ean' => $line['ean'],
                    'amount' => $line['amount_ordered'],
                ];
            }, $order['order_lines']),
            'zpl_direct' => true
        ];

        // 3. roept api aan met username en wachtwoord 
        $response = Http::withBasicAuth('frits@test.qlsnet.nl', '4QJW9yh94PbTcpJGdKz6egwH')
            ->post("https://api.pakketdienstqls.nl/v2/companies/{$companyId}/shipments", $payload);

        // 4. verwerk response naar pdf 
        if ($response->successful()) {
            $json = $response->json();
            $labelPdfUrl = $json['data']['label_pdf_url'] ?? null;
            return back()
                ->with('success', 'Label aangemaakt!')
                ->with('label_pdf_url', $labelPdfUrl);
        } else {
            return back()->with('error', 'Label aanmaken mislukt!')->with('api_error', $response->body());
        }
    }
}