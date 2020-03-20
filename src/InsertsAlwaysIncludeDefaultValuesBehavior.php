<?php

declare(strict_types=1);

namespace prgTW\Propel\Behavior;

use Propel\Generator\Builder\Om\ObjectBuilder;
use Propel\Generator\Model\Behavior;

class InsertsAlwaysIncludeDefaultValuesBehavior extends Behavior
{
	public function objectFilter(string &$script, ObjectBuilder $objectBuilder)
	{
		$table = $objectBuilder->getTable();
		foreach ($table->getColumns() as $column) {
			if (!$column->hasDefaultValue()) {
				continue;
			}

			$constantName = preg_quote($objectBuilder->getColumnConstant($column), '/');

			$regex  = '^(\\s*)if\\s*\\(\\s*\\$this->isColumnModified\\(\\s*'.$constantName.'\\s*\\)\\s*\\)\\s*{\\n\\1\\s*(\$modifiedColumns\[\':p\'.+?)\\n\\1\\s*}';
			$script = preg_replace("/$regex/uim", '$1$2', $script);
		}
	}
}
