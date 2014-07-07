<?php

use Model_Promocje_OrderStatus as Status;

class Model_Promocje_OrderStatusMapper
{
    const STATUS_NOWE = 1;
    const STATUS_CZEKA_NA_ZATWIERDZENIE = 2;
    const STATUS_POTWIERDZONE = 3;
    const STATUS_ODRZUCONE = 4;
    const STATUS_DO_POPRAWY = 5;

    public static function getAll()
    {
        return array(
            self::STATUS_NOWE => new Status(self::STATUS_NOWE, 'nowe'),
            self::STATUS_CZEKA_NA_ZATWIERDZENIE => new Status(self::STATUS_CZEKA_NA_ZATWIERDZENIE, 'czeka na zatwierdzenie'),
            self::STATUS_POTWIERDZONE => new Status(self::STATUS_POTWIERDZONE, 'potwierdzone'),
            self::STATUS_ODRZUCONE => new Status(self::STATUS_ODRZUCONE, 'odrzucone'),
            self::STATUS_DO_POPRAWY => new Status(self::STATUS_DO_POPRAWY, 'do poprawy')
        );
    }
}
