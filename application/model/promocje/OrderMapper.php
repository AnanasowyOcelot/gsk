<?php

class Model_Promocje_OrderMapper extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'promocje_orders';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_Promocje_OrderEntity';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id' => array('id', Core_Mapper::T_INT),
            'clientAppId' => array('client_app_id', Core_Mapper::T_VARCHAR),
            'statusId' => array('status_id', Core_Mapper::T_INT),
            'przedstawicielId' => array('przedstawiciel_id', Core_Mapper::T_INT),
            'nextEditorId' => array('next_editor_id', Core_Mapper::T_INT),
            'nextEditorName' => array('next_editor_name', Core_Mapper::T_VARCHAR),
            'promotionId' => array('promocja_id', Core_Mapper::T_INT),
            'addressId' => array('address_id', Core_Mapper::T_INT),
            'dystrybutorId' => array('dystrybutor_id', Core_Mapper::T_INT),
            'dataUtworzenia' => array('data_utworzenia', Core_Mapper::T_DATETIME_CREATED),
            'dataAktualizacji' => array('data_aktualizacji', Core_Mapper::T_DATETIME_UPDATED)
        );
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function save(Model_Promocje_OrderEntity $o)
    {
        parent::save($o);

        $db = Core_DB::instancja();

        $db->query('DELETE FROM `promocje_orders_items`
            WHERE `order_id` = "' . self::escape($o->id) . '"');

        foreach ($o->items as $item) {
            $item['orderId'] = $o->id;
            $db->insert(
                'promocje_orders_items',
                array(
                    'order_id' => $item['orderId'],
                    'stage_id' => $item['stageId'],
                    'amount' => $item['amount']
                )
            );
        }

        $db->query('DELETE FROM `promocje_orders_comments`
            WHERE `order_id` = "' . self::escape($o->id) . '"');

        foreach ($o->items as $comment) {
            $comment['orderId'] = $o->id;
            $db->insert(
                'promocje_orders_comments',
                array(
                    'order_id' => $comment['orderId'],
                    'text' => $comment['text'],
                    'time' => $comment['time'],
                    'author_id' => $comment['authorId'],
                    'author_name' => $comment['authorName']
                )
            );
        }
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function buildObject(array $tableRow)
    {
        $o = parent::buildObject($tableRow);

        $db = Core_DB::instancja();
        //$db->query('SELECT * FROM `promocje_orders_items`
        //    WHERE `order_id` = "' . static::escape($o->id) . '"');

        $o->items = array();
        if ($o->id > 0) {
            $res = $db->query('SELECT * FROM `promocje_orders_items`
                WHERE `order_id` = "' . static::escape($o->id) . '"');
            foreach ($res as $item) {
                $o->items[$item['stage_id']] = array(
                    'orderId' => $item['order_id'],
                    'stageId' => $item['stage_id'],
                    'amount' => $item['amount']
                );
            }
        }

        $o->comments = array();
        if ($o->id > 0) {
            $res = $db->query('SELECT * FROM `promocje_orders_comments`
                WHERE `order_id` = "' . static::escape($o->id) . '"');
            foreach ($res as $item) {
                $o->comments[] = array(
                    'orderId' => $item['order_id'],
                    'text' => $item['text'],
                    'time' => $item['time'],
                    'authorId' => $item['author_id'],
                    'authorName' => $item['author_name']
                );
            }
        }

        return $o;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    /**
     * @param array $r
     * @param null $object
     * @return Model_Promocje_OrderEntity
     */
    public function fromArray(array $r, $object = null)
    {
        $o = parent::fromArray($r, $object);
        $o->items = $r['items'];
        return $o;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function findAllForUser(Model_App_UserEntity $user)
    {
        $db = Core_DB::instancja();
        $res = $db->query('SELECT * FROM `promocje_orders`
            WHERE
                `przedstawiciel_id` = "' . static::escape($user->id) . '"
                OR `next_editor_id` = "' . static::escape($user->id) . '"
            ');
        $orders = array();
        foreach ($res as $row) {
            $orders[] = static::buildObject($row);
        }
        return $orders;
    }
}
