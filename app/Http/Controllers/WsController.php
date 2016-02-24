<?php

namespace App\Http\Controllers;

use Artisaninweb\SoapWrapper\Facades\SoapWrapper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WsController extends Controller
{
    public function __construct()
    {
    }

    public function test()
    {
        SoapWrapper::add(function($service) {
            $service
                ->name('currency')
                ->wsdl('http://currencyconverter.kowabunga.net/converter.asmx?WSDL')
                ->trace(true)                                                   // Optional: (parameter: true/false)
                // ->header()                                                      // Optional: (parameters: $namespace,$name,$data,$mustunderstand,$actor)
                // ->customHeader($customHeader)                                   // Optional: (parameters: $customerHeader) Use this to add a custom SoapHeader or extended class
                // ->cookie()                                                      // Optional: (parameters: $name,$value)
                // ->location()                                                    // Optional: (parameter: $location)
                // ->certificate()                                                 // Optional: (parameter: $certLocation)
                ->cache(WSDL_CACHE_NONE)                                        // Optional: Set the WSDL cache
                ->options(['login' => 'username', 'password' => 'password']);   // Optional: Set some extra options
        });

        $data = [
            'CurrencyFrom' => 'USD',
            'CurrencyTo'   => 'EUR',
            'RateDate'     => '2014-06-05',
            'Amount'       => '1000'
        ];

        // Using the added service
        SoapWrapper::service('currency', function ($service) use ($data) {
            var_dump($service->getFunctions());
            var_dump($service->call('GetConversionAmount', [$data])->GetConversionAmountResult);
        });
    }

    public function wsClient()
    {
        SoapWrapper::add(function($service) {
            $service
                ->name('customer')
                ->wsdl('http://10.16.11.16:8077/WS/WebServiceSudamericana.asmx?WSDL')
                ->trace(true)
                ->cache(WSDL_CACHE_NONE)
                ->options(['wPwd' => 't874j563bk580fghu']);
        });

        $data = [
            'wPwd'   => 't874j563bk580fghu',
            'wDocId' => '6929262',
        ];

        SoapWrapper::service('customer', function ($service) use ($data) {
            // var_dump($service->getFunctions());
            $response = $service->call('su_PersonaGetByDocId', [$data]);

            dd(explode('|', $response->su_PersonaGetByDocIdResult));
        });
    }

}
