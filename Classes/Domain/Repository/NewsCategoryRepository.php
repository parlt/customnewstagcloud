<?php
namespace TYPO3\Customnewstagcloud\Domain\Repository;

	/***************************************************************
	 *  Copyright notice
	 *
	 *  (c) 2014 Pierre Arlt <info@pierrearlt.com>, Grafik, Entwicklung & IT Service | Pierre Arlt
	 *
	 *  All rights reserved
	 *
	 *  This script is part of the TYPO3 project. The TYPO3 project is
	 *  free software; you can redistribute it and/or modify
	 *  it under the terms of the GNU General Public License as published by
	 *  the Free Software Foundation; either version 3 of the License, or
	 *  (at your option) any later version.
	 *
	 *  The GNU General Public License can be found at
	 *  http://www.gnu.org/copyleft/gpl.html.
	 *
	 *  This script is distributed in the hope that it will be useful,
	 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
	 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 *  GNU General Public License for more details.
	 *
	 *  This copyright notice MUST APPEAR in all copies of the script!
	 ***************************************************************/

/**
 *
 *
 * @package customnewstagcloud
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class NewsCategoryRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {


	const LEFT_JOIN_LIMIT = 62;

	/**
	 * @param int $categoryUid
	 * @return int
	 */
	public function getWightValue($categoryUid) {

		$weight = 1;
		/** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
		$query = $this->createQuery();
		$querySettings = $query->getQuerySettings();
		$querySettings->setIgnoreEnableFields(TRUE);
		$querySettings->setReturnRawQueryResult(TRUE);
		$querySettings->setRespectSysLanguage(FALSE);

		$query->statement('SELECT COUNT(uid_foreign) AS weight FROM tt_news_cat_mm WHERE uid_foreign=' . $categoryUid);
		$result = $query->execute();

		if (isset($result[0]['weight'])) {
			$weight = intval($result[0]['weight']);
		}

		return $weight;
	}

	/**
	 * @param $currentLangUid
	 * @param array $categoryIds
	 * @param $nestingLevel
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findAllIfHasActiveNews($currentLangUid, array $categoryIds, $nestingLevel) {
		/** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
		$query = $this->createQuery();
		$querySettings = $query->getQuerySettings();
		$querySettings->setIgnoreEnableFields(TRUE);
		$querySettings->setReturnRawQueryResult(FALSE);
		$querySettings->setRespectSysLanguage(FALSE);

		$treeUids = $this->getTreeIds($categoryIds, $nestingLevel);
		$treeUids = array_merge($categoryIds, $treeUids);
		$treeUids = array_unique($treeUids);
		sort($treeUids, ksort($treeUids));

		$query->statement('SELECT DISTINCT newscat.uid, newscat.pid,newscat.hidden, newscat.starttime, newscat.title,
                                  newscat.title_lang_ol, newscat.single_pid, newscat.parent_category
                           from tt_news_cat as newscat
                           LEFT JOIN tt_news_cat_mm AS newscatmm ON newscatmm.uid_foreign = newscat.uid
                           LEFT JOIN tt_news AS news ON news.uid = newscatmm.uid_local
                           WHERE newscat.deleted < 1
                           AND newscat.starttime < CURRENT_TIMESTAMP()
                           AND newscat.hidden < 1 AND news.uid IS NOT NULL
                           AND news.starttime < CURRENT_TIMESTAMP()
                           AND news.hidden < 1 AND news.sys_language_uid ='
			. $currentLangUid . ' ' . $this->getAndWhereStr($treeUids));

		$result = $query->execute();

		return $result;
	}

	/**
	 * @param array $categoryIds
	 * @param $nestingLevel
	 * @return array
	 */
	private function getTreeIds(array $categoryIds, $nestingLevel) {

		$categoryIdsStr = '	level1.parent_category =' . implode(' OR level1.parent_category =', $categoryIds);

		/** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
		$query = $this->createQuery();
		$querySettings = $query->getQuerySettings();
		$querySettings->setIgnoreEnableFields(TRUE);
		$querySettings->setReturnRawQueryResult(TRUE);
		$querySettings->setRespectSysLanguage(FALSE);

		$query->statement('SELECT ' . $this->getRecursiveSelectString($nestingLevel) . '
                             from tt_news_cat as level1
                            ' . $this->getRecursiveLeftJoinStr($nestingLevel) . '
                            where ' . $categoryIdsStr);

		$result = $query->execute();
		$data = array();
		foreach ($result as $resultItem) {
			foreach ($resultItem as $resultInnerItem) {
				if (!empty($resultInnerItem)) {
					$data[] = $resultInnerItem;
				}
			}
		}

		$data = array_unique($data);
		sort($data, ksort($data));

		return $data;
	}

	/**
	 * @param array $treeUids
	 * @return string
	 */
	private function  getAndWhereStr(array $treeUids) {
		$andWhereStr = 'AND (';
		$length = count($treeUids);
		$i = 0;
		while ($i < $length) {
			$andWhereStr .= 'newscat.uid =' . $treeUids[$i];
			if ($i < $length - 1) {
				$andWhereStr .= ' OR';
			}

			$andWhereStr .= ' ';
			$i++;
		}

		$andWhereStr .= ') ';

		return $andWhereStr;
	}

	/**
	 * @param int $nestingLevel
	 * @return string
	 */
	private function getRecursiveSelectString($nestingLevel) {
		$selectStr = '';
		$i = 1;
		while ($i <= $nestingLevel && $i < self::LEFT_JOIN_LIMIT) {
			$selectStr .= ' level' . $i . '.uid AS uid_level_' . $i;
			if ($i < $nestingLevel && $i < self::LEFT_JOIN_LIMIT - 1) {
				$selectStr .= ',';
			}
			$selectStr .= ' ';

			$i++;
		}

		return $selectStr;
	}

	/**
	 * @param int $nestingLevel
	 * @return string
	 */
	private function getRecursiveLeftJoinStr($nestingLevel) {
		$leftJoinStr = '';
		$i = 2;
		while ($i <= $nestingLevel && $i < self::LEFT_JOIN_LIMIT) {
			$leftJoinStr .= 'LEFT JOIN tt_news_cat as level' . $i . ' ON level'
				. $i . '.parent_category = level' . ($i - 1) . '.uid';
			$leftJoinStr .= ' ';
			$i++;
		}

		return $leftJoinStr;
	}

	/**
	 * @see http://stackoverflow.com/questions/1319903/how-to-flatten-a-multidimensional-array
	 * @param $array
	 * @return array
	 */
	private function array_flatten($array) {
		$return = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$return = array_merge($return, $this->array_flatten($value));
			} else {
				$return[$key] = $value;
			}
		}

		return $return;

	}

}

?>