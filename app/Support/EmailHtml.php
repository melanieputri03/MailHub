<?php

namespace App\Support;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class EmailHtml
{
    public static function inlineCss(?string $html, ?string $css): string
    {
        if (!$html || trim($html) === '') return '';

        $css = preg_replace('/(^|\})\s*(\*|body)\s*\{[^}]*\}/i', '$1', $css ?? '');

        $full = (new CssToInlineStyles())->convert($html, $css);

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $full);
        libxml_clear_errors();

        $out = '';
        foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        $out = preg_replace('/\sid="i[a-z0-9]{3,6}"/i', '', $out);        
        $out = preg_replace('/\sdata-(path|index)-[a-z-]*="[^"]*"/i', '', $out); 

        return trim($out);
    }
}