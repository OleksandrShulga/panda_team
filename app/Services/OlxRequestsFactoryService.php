<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;

class OlxRequestsFactoryService
{

    public static function init($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Слідуємо за редиректами
        $html = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Отримати актуальну інформацію по вашій заявці на стеження не вдалося, оскільки: ' . curl_error($ch);
        }
        curl_close($ch);

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);
        $elements = $xpath->query("//h3[contains(@class, 'css-90xrc0')]");

        if ($elements->length) {
            $element = $elements->item(0);
            $value = $element->textContent;
            return $value;
        } else {
            return "Відслідковування ціни для неможиве, оскільки оголошення вже неактуалтне або ціна була схована";
        }
    }
}
