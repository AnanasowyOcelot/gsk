<?php

class Model_Promocje_OrderStatus
{
    public $id = 0;
    public $nazwa = '';

    public function __construct($id, $nazwa) {
        $this->id = $id;
        $this->nazwa = $nazwa;
    }
}
