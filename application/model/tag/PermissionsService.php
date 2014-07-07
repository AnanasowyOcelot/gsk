<?php

class Model_Tag_PermissionsService
{
	public static function filter(array $resources, $agent)
	{
		$filteredResources = array();
		foreach ($resources as $resource) {
			if (static::isAllowed($resource, $agent)) {
				$filteredResources[] = $resource;
			}
		}
		return $filteredResources;
	}

	private static function isAllowed($resource, $agent)
	{
		$isAllowed   = true;
		$rTags       = $resource->getTags();
		$aTags       = $agent->getTags();
		$groupedTags = array();
		foreach ($rTags as $rTag) {
			if (!isset($groupedTags[$rTag->groupId])) {
				$groupedTags[$rTag->groupId] = array();
			}
			$groupedTags[$rTag->groupId][] = $rTag;
		}
		foreach ($groupedTags as $tags) {
			$isGroupAllowed = false;
			foreach ($tags as $rTag) {
				if (in_array($rTag, $aTags)) {
					$isGroupAllowed = true;
				}
			}
			if ($isGroupAllowed == false) {
				$isAllowed = false;
			}
		}
		return $isAllowed;
	}
}
