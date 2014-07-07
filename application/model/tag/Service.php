<?php

class Model_Tag_Service
{
    public static function getTagIdsForObject($o)
    {
        $db         = Core_DB::instancja();
        $stmtDelete = $db->Prepare('SELECT tag_id AS id
            FROM tags_connections
            WHERE
                connected_record_id = ?
                AND connected_record_class = ?
            ');
        $rows       = $db->Execute($stmtDelete, array(
            (int)$o->id,
            get_class($o)
        ));
        $tagIds     = array();
        foreach ($rows as $row) {
            $tagIds[] = $row['id'];
        }
        return $tagIds;
    }

    public static function getTags($tagIds)
    {
        $tags = array();
        $tagMapper = new Model_Tag_TagMapper();
        foreach($tagIds as $tagId) {
            $tags[] = $tagMapper->findOneById($tagId);
        }
        return $tags;
    }

    public static function getTagsNotInList($tagIds)
    {
        $tagMapper = new Model_Tag_TagMapper();
        $allTags = $tagMapper->find();
        $tags = array();
        foreach ($allTags as $tag) {
            if(!in_array($tag->id, $tagIds)) {
                $tags[] = $tag;
            }
        }
        return $tags;
    }

    public static function saveTagIdsForObject($o, $tagIds)
    {
        $db         = Core_DB::instancja();
        $stmtDelete = $db->Prepare('DELETE FROM tags_connections
            WHERE
                connected_record_id = ?
                AND connected_record_class = ?
            ');
        $db->Execute($stmtDelete, array(
            (int)$o->id,
            get_class($o)
        ));

        $stmtInsert = $db->Prepare('INSERT INTO tags_connections
            SET
                tag_id = ?,
                connected_record_id = ?,
                connected_record_class = ?
            ');
        foreach ($tagIds as $tagId) {
            $db->Execute($stmtInsert, array(
                (int)$tagId,
                (int)$o->id,
                get_class($o)
            ));
        }
    }
}
