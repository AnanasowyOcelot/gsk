<?php

class Model_App_LogMapper extends Core_Mapper
{
    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getTable()
    {
        return 'app_log';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDataObjectClass()
    {
        return 'Model_App_Log';
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
    public function getDescription()
    {
        return array(
            'id' => array('id', Core_Mapper::T_INT),
            'userId' => array('user_id', Core_Mapper::T_INT),
            'activity' => array('activity', Core_Mapper::T_VARCHAR),
            'apiVersion' => array('api_version', Core_Mapper::T_VARCHAR),
            'appVersion' => array('app_version', Core_Mapper::T_VARCHAR),
            'time' => array('time', Core_Mapper::T_DATETIME_CREATED)
        );
    }
}
