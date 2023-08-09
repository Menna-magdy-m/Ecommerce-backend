<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class QueryBuilder
{
	public static function onFilter(Builder $qb, string $name, array $filter, string $boolean = 'and'): Builder
	{
		$chunks = explode('.', $name);
		$operator = isset($filter['operation']) ? $filter['operation'] : '=';
		if (count($chunks) === 1) {
			$qb = $qb->where($chunks[0], $operator, $filter['value'], $boolean);
		} else if (count($chunks) === 2) {
			$qb = $qb->whereHas($chunks[0], fn($relation) => $relation->where($chunks[1], $operator, $filter['value'], $boolean));
		} else {
			throw new \Exception('qb:filter:max_depth_exceeded');
		}
		return $qb;
	}
}
