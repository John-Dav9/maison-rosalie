<?php

declare(strict_types=1);

namespace model;

use InvalidArgumentException;

/** Normalise les champs texte du formulaire afin de ne stocker que du texte brut. */
final class ContactText
{
    public static function plainText(string $value, bool $singleLine = false): string
    {
        if (preg_match('//u', $value) !== 1) {
            throw new InvalidArgumentException('Le texte doit être encodé en UTF-8 valide.');
        }

        // Décode avant strip_tags pour neutraliser aussi les balises encodées en entités HTML.
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = strip_tags($value);
        $value = str_replace(["\r\n", "\r"], "\n", $value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value) ?? '';

        if ($singleLine) {
            $value = str_replace("\n", ' ', $value);
            $value = preg_replace('/[\t ]+/', ' ', $value) ?? '';
        }

        return trim($value);
    }
}
