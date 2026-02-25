<?php

namespace App\Services\Support;

class Sanitizer
{
    /**
     * Texto simples: remove tags, normaliza espaços.
     */
    public static function plain(?string $value, int $maxLen = 5000): ?string
    {
        if ($value === null) return null;
        $v = trim($value);
        $v = strip_tags($v);
        $v = preg_replace('/\s+/u', ' ', $v);
        if ($maxLen > 0) $v = mb_substr($v, 0, $maxLen);
        $v = trim($v);
        return $v === '' ? null : $v;
    }

    /**
     * Chaves curtas: normaliza e permite apenas a-z 0-9 _
     */
    public static function key(?string $value, int $maxLen = 80): ?string
    {
        $v = self::plain($value, $maxLen);
        if ($v === null) return null;
        $v = strtolower($v);
        $v = preg_replace('/[^a-z0-9_]+/u', '_', $v);
        $v = trim($v, '_');
        return $v === '' ? null : $v;
    }

    /**
     * Rich text (HTML): remove blocos perigosos e handlers on* e javascript:.
     */
    public static function rich(?string $html, int $maxLen = 500000): ?string
    {
        if ($html === null) return null;
        $v = trim($html);
        // remove script/style/iframe/object/embed
        $v = preg_replace('/<\s*(script|style|iframe|object|embed)\b[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $v);
        // remove handlers on*=
        $v = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[\s\S]*?\')/i', '', $v);
        // neutraliza javascript: em href/src
        $v = preg_replace('/\s(href|src)\s*=\s*("|\')\s*javascript:[^"\']*(\2)/i', ' $1=$2#$2', $v);
        if ($maxLen > 0) $v = mb_substr($v, 0, $maxLen);
        $v = trim($v);
        return $v === '' ? null : $v;
    }
}
