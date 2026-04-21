<?php

namespace App\Enums;

enum Emulator: string
{
    case TRINITYCORE = 'TC';
    case AZEROTHCORE = 'AC';

    public const LABELS = [
        self::TRINITYCORE->value => 'TrinityCore',
        self::AZEROTHCORE->value => 'AzerothCore',
    ];

    public const DESCRIPTIONS = [
        self::TRINITYCORE->value => 'TrinityCore es un emulador de servidor de World of Warcraft que se centra en la estabilidad y el rendimiento, ofreciendo una experiencia de juego fluida y sin interrupciones.',
        self::AZEROTHCORE->value => 'AzerothCore es un fork de TrinityCore que se enfoca en la innovación y la adición de nuevas características, proporcionando una experiencia de juego más dinámica y personalizada.',
    ];

    public const URN = [
        self::TRINITYCORE->value => 'TC',
        self::AZEROTHCORE->value => 'AC',
    ];
}
