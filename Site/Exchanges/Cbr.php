<?php

namespace ServiceBoiler\Prf\Site\Exchanges;

use ServiceBoiler\Prf\Site\Contracts\Exchange;

class Cbr implements Exchange
{

    protected $data = [];

    public function __construct()
    {
        $context = stream_context_create(
            array(
                'http' => array(
                    'max_redirects' => 101
                )
            )
        );
        $file = simplexml_load_file("http://www.cbr.ru/scripts/XML_daily.asp");
        if ($file === false) {
            // TODO: failed load file from http://cbr.ru
        } else {

            foreach ($file AS $currency) {

                $this->data[strval($currency->NumCode)] = [
                    'name'         => strval($currency->CharCode),
                    'title'        => strval($currency->Name),
                    'rates'        => str_replace(',', '.', strval($currency->Value)),
                    'multiplicity' => strval($currency->Nominal),
                ];
            }
        }

    }

    /**
     * @return array
     */
    function all(): array
    {
        return $this->data;
    }

    /**
     * @param $id
     * @return array
     */
    function get($id): array
    {
        if (key_exists($id, $this->data)) {
            return $this->data[$id];
        }

        return [];
    }
}