<?php

if (! function_exists('remove_accents_uppercase')) {
    function remove_accents_uppercase($string)
    {
        // Remplacer uniquement les accents (en préservant les caractères spéciaux comme le "°")
        $unaccented_string = preg_replace(
            '/[áàäâãå]/u', 'a',
            preg_replace(
                '/[éèëê]/u', 'e',
                preg_replace(
                    '/[íìïî]/u', 'i',
                    preg_replace(
                        '/[óòöôõ]/u', 'o',
                        preg_replace(
                            '/[úùüû]/u', 'u',
                            preg_replace(
                                '/[ç]/u', 'c',
                                $string
                            )
                        )
                    )
                )
            )
        );

        // Convertir tout en majuscules
        return mb_strtoupper($unaccented_string, 'UTF-8');
    }
}

if (! function_exists('format_proper_name')) {
    function format_proper_name($string)
    {
        // Remplacer uniquement les accents (en préservant les caractères spéciaux comme le "°")
        $unaccented_string = preg_replace(
            '/[áàäâãå]/u', 'a',
            preg_replace(
                '/[éèëê]/u', 'e',
                preg_replace(
                    '/[íìïî]/u', 'i',
                    preg_replace(
                        '/[óòöôõ]/u', 'o',
                        preg_replace(
                            '/[úùüû]/u', 'u',
                            preg_replace(
                                '/[ç]/u', 'c',
                                $string
                            )
                        )
                    )
                )
            )
        );

        // Convertir tout en minuscule et mettre en majuscule la première lettre de chaque mot
        return ucwords(strtolower($unaccented_string));
    }
}
