<?php

namespace TYPO3\Customnewstagcloud\Tests;
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
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage Custom tt_news Tagcloud
 *
 * @author Pierre Arlt <info@pierrearlt.com>
 */
class NewsCategoryTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getUidReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getUid()
		);
	}

	/**
	 * @test
	 */
	public function setUidForIntegerSetsUid() { 
		$this->fixture->setUid(12);

		$this->assertSame(
			12,
			$this->fixture->getUid()
		);
	}
	
	/**
	 * @test
	 */
	public function getPidReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getPid()
		);
	}

	/**
	 * @test
	 */
	public function setPidForIntegerSetsPid() { 
		$this->fixture->setPid(12);

		$this->assertSame(
			12,
			$this->fixture->getPid()
		);
	}
	
	/**
	 * @test
	 */
	public function getHiddenReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getHidden()
		);
	}

	/**
	 * @test
	 */
	public function setHiddenForIntegerSetsHidden() { 
		$this->fixture->setHidden(12);

		$this->assertSame(
			12,
			$this->fixture->getHidden()
		);
	}
	
	/**
	 * @test
	 */
	public function getStarttimeReturnsInitialValueForDateTime() { }

	/**
	 * @test
	 */
	public function setStarttimeForDateTimeSetsStarttime() { }
	
	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle() { 
		$this->fixture->setTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTitle()
		);
	}
	
	/**
	 * @test
	 */
	public function getTitleLangOlReturnsInitialValueForString() { }

	/**
	 * @test
	 */
	public function setTitleLangOlForStringSetsTitleLangOl() { 
		$this->fixture->setTitleLangOl('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getTitleLangOl()
		);
	}
	
	/**
	 * @test
	 */
	public function getSinglePidReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getSinglePid()
		);
	}

	/**
	 * @test
	 */
	public function setSinglePidForIntegerSetsSinglePid() { 
		$this->fixture->setSinglePid(12);

		$this->assertSame(
			12,
			$this->fixture->getSinglePid()
		);
	}
	
	/**
	 * @test
	 */
	public function getParentCategoryReturnsInitialValueForInteger() { 
		$this->assertSame(
			0,
			$this->fixture->getParentCategory()
		);
	}

	/**
	 * @test
	 */
	public function setParentCategoryForIntegerSetsParentCategory() { 
		$this->fixture->setParentCategory(12);

		$this->assertSame(
			12,
			$this->fixture->getParentCategory()
		);
	}
	
}
?>