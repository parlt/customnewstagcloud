<?php
namespace TYPO3\Customnewstagcloud\Domain\Model;

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
class NewsCategory extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * uid
	 *
	 * @var \integer
	 * @validate NotEmpty
	 */
	protected $uid;

	/**
	 * pid
	 *
	 * @var \integer
	 */
	protected $pid;

	/**
	 * hidden
	 *
	 * @var \integer
	 */
	protected $hidden;

	/**
	 * starttime
	 *
	 * @var \DateTime
	 */
	protected $starttime;

	/**
	 * title
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $title;

	/**
	 * $uri
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $uri;

	/**
	 * titleLangOl
	 *
	 * @var \string
	 */
	protected $titleLangOl;


	/**
	 * wight
	 *
	 * @var \integer
	 */
	protected $wight;

	/**
	 * singlePid
	 *
	 * @var \integer
	 */
	protected $singlePid;

	/**
	 * parentCategory
	 *
	 * @var \integer
	 */
	protected $parentCategory;

	/**
	 * Returns the uid
	 *
	 * @return \integer $uid
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * Sets the uid
	 *
	 * @param \integer $uid
	 * @return void
	 */
	public function setUid($uid) {
		$this->uid = $uid;
	}

	/**
	 * Returns the pid
	 *
	 * @return \integer $pid
	 */
	public function getPid() {
		return $this->pid;
	}

	/**
	 * Sets the pid
	 *
	 * @param \integer $pid
	 * @return void
	 */
	public function setPid($pid) {
		$this->pid = $pid;
	}

	/**
	 * Returns the hidden
	 *
	 * @return \integer $hidden
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * Sets the hidden
	 *
	 * @param \integer $hidden
	 * @return void
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * Returns the starttime
	 *
	 * @return \DateTime $starttime
	 */
	public function getStarttime() {
		return $this->starttime;
	}

	/**
	 * Sets the starttime
	 *
	 * @param \DateTime $starttime
	 * @return void
	 */
	public function setStarttime($starttime) {
		$this->starttime = $starttime;
	}

	/**
	 * Returns the title
	 *
	 * @return \string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param \string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the titleLangOl
	 *
	 * @return \string $titleLangOl
	 */
	public function getTitleLangOl() {
		return $this->titleLangOl;
	}

	/**
	 * Sets the titleLangOl
	 *
	 * @param \string $titleLangOl
	 * @return void
	 */
	public function setTitleLangOl($titleLangOl) {
		$this->titleLangOl = $titleLangOl;
	}

	/**
	 * Returns the singlePid
	 *
	 * @return \integer $singlePid
	 */
	public function getSinglePid() {
		return $this->singlePid;
	}

	/**
	 * Sets the singlePid
	 *
	 * @param \integer $singlePid
	 * @return void
	 */
	public function setSinglePid($singlePid) {
		$this->singlePid = $singlePid;
	}

	/**
	 * Returns the parentCategory
	 *
	 * @return \integer $parentCategory
	 */
	public function getParentCategory() {
		return $this->parentCategory;
	}

	/**
	 * Sets the parentCategory
	 *
	 * @param \integer $parentCategory
	 * @return void
	 */
	public function setParentCategory($parentCategory) {
		$this->parentCategory = $parentCategory;
	}

	/**
	 * @param string $uri
	 */
	public function setUri($uri) {
		$this->uri = $uri;
	}

	/**
	 * @return string
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 * @param int $wight
	 */
	public function setWight($wight) {
		$this->wight = $wight;
	}

	/**
	 * @return int
	 */
	public function getWight() {
		return $this->wight;
	}


}

?>