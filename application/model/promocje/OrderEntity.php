<?php

class Model_Promocje_OrderEntity
{
    public $id = 0;
    public $clientAppId = '';
    public $statusId = null;
    public $przedstawicielId = null;
    public $przedstawiciel = null;
    public $nextEditorId = 0;
    public $nextEditorName = '';
    public $promotionId = null;
    public $addressId = null;
    public $dystrybutorId = null;
    public $dystrybutor = null;
    public $dataUtworzenia = '';
    public $dataAktualizacji = '';

    public $items = array();
}
