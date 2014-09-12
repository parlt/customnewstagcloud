<?php
namespace TYPO3\Customnewstagcloud\Controller;

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
 * @package customnewstagcloud
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class NewsCategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * newsCategoryRepository
	 *
	 * @var \TYPO3\Customnewstagcloud\Domain\Repository\NewsCategoryRepository
	 * @inject
	 */
	protected $newsCategoryRepository;

	/** @var integer */
	private $newsCategoryMenuPid = NULL;

	/**
	 * @return \tslib_fe
	 */
	private static function getTsFe() {
		return $GLOBALS['TSFE'];
	}


	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {

		/** @var  \TYPO3\Customnewstagcloud\Domain\Repository\NewsCategoryRepository $newsCategories */
		$newsCategories = NULL;
		$newsCategoriesWithLink = array();
		$width = 300;
		$height = 300;
		$cssId = $this->settings['cssId'];

		$this->newsCategoryMenuPid = $this->settings['tt_newsCategoryMenuPid'];
		$nestingLevel = $this->settings['tt_newsCategoryNestinglevel'];

		$categoryIds = $this->settings['categorySelection'];
		if (!empty($categoryIds) && !empty($nestingLevel)) {
			$categoryIds = explode(',', $categoryIds);

			$newsCategories = $this->newsCategoryRepository
				->findAllIfHasActiveNews(self::getTsFe()->sys_language_uid, $categoryIds, $nestingLevel);

			foreach ($newsCategories as $newsCategory) {
				$newsCategory = $this->buildLink($newsCategory);
				$newsCategory = $this->buildTitel($newsCategory);
				$newsCategory = $this->setWeightValueIfActive($newsCategory);
				$newsCategoriesWithLink[] = $newsCategory;
			}

			if (isset($this->settings['width']) && trim($this->settings['width']) != '') {
				$width = $this->settings['width'];
			}

			if (isset($this->settings['height']) && trim($this->settings['height']) != '') {
				$height = $this->settings['height'];
			}

			$containerId = $cssId . '_container';
			$this->injectHeaderData($cssId, $containerId);

			$this->view->assign('cssId', $cssId);
			$this->view->assign('width', $width);
			$this->view->assign('height', $height);
			$this->view->assign('containerId', $containerId);
			$this->view->assign('newsCategories', $newsCategories);
		} else {
			$flashMessage = $this->objectManager->get(
				'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
				'Please select a Category and set Recursion > 0',
				\TYPO3\CMS\Core\Messaging\FlashMessage::OK,
				TRUE
			);
			$this->controllerContext->getFlashMessageQueue()->enqueue(
				$flashMessage
			);
		}
	}


	/**
	 * @param \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory $newsCategory
	 * @return \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory $newsCategory
	 */
	private function setWeightValueIfActive($newsCategory) {
		$weightActive = $this->settings['weightactive'];
		if (!empty($weightActive) && $weightActive = '1') {
			$weightValue = $this->newsCategoryRepository->getWightValue($newsCategory->getUid());
			$newsCategory->setWight($weightValue);
		}

		return $newsCategory;
	}


	/**
	 * @param \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory $newsCategory
	 * @return \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory $newsCategory
	 */
	private function buildTitel($newsCategory) {
		if ($this->getTsFe()->sys_language_uid != 0) {
			$langUid = $this->getTsFe()->sys_language_uid;
			$overlayTitleStr = $newsCategory->getTitleLangOl();
			$overlayTitleData = explode('|', $overlayTitleStr);

			if (isset($overlayTitleData[$langUid - 1]) && ($overlayTitleData[$langUid - 1] != '')) {
				$newsCategory->setTitle($overlayTitleData[$langUid - 1]);
			}
		}

		return $newsCategory;
	}


	/**
	 * @param \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory $newsCategory
	 * @return \TYPO3\Customnewstagcloud\Domain\Model\NewsCategory $newsCategory
	 */
	private function buildLink($newsCategory) {

		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder->setTargetPageUid($this->newsCategoryMenuPid)
			->setNoCache(FALSE)
			->setUseCacheHash(TRUE)
			->setCreateAbsoluteUri(TRUE)
			->setArguments(array('tx_ttnews' => array('cat' => $newsCategory->getUid())))
			->buildFrontendUri();

		$newsCategory->setUri($uri);

		return $newsCategory;
	}


	/**
	 * Build and inject header codes
	 *
	 * @param string $cssId
	 * @param string $containerId
	 * @return null
	 */
	private function injectHeaderData($cssId, $containerId) {
		$jQueryPrefix = 'jQuery';
		$backgroundColor = 'transparent';

		/** @var string $jsOptions */
		$jsOptions = $this->getJsOptions();

		if (!isset($this->settings['jqueryNoConflict']) || $this->settings['jqueryNoConflict'] == '0') {
			$jQueryPrefix = '$';
		}

		if (isset($this->settings['backgroundcolor']) && trim($this->settings['backgroundcolor']) != '') {
			$backgroundColor = $this->settings['backgroundcolor'];
		}

		$jsStr = $jQueryPrefix . '(document).ready(function () {
                if (!' . $jQueryPrefix . '(\'#' . $cssId . '\').tagcanvas({
                  ' . $jsOptions . '
                })) {
                    ' . $jQueryPrefix . '(\'#' . $containerId . '\').hide();
                }
            });';
		$jsStr = ' <script type="text/javascript">' . $jsStr . '   </script>';
		$cssStr = '#' . $containerId . '{
                  background:' . $backgroundColor . '
       }';

		$cssStr = '<style type="text/css">' . $cssStr . '</style>';

		self::getTsFe()->additionalHeaderData['customnewstagcloud_' . $cssId . '_css'] = $cssStr;
		if (isset($this->settings['javaScriptPosition']) && $this->settings['javaScriptPosition'] == 'footer') {
			self::getTsFe()->additionalFooterData['customnewstagcloud_' . $cssId . '_js'] = $jsStr;
		} else {
			self::getTsFe()->additionalHeaderData['customnewstagcloud_' . $cssId . '_js'] = $jsStr;
		}

		return NULL;
	}


	/**
	 * Get configuration from flexform
	 *
	 * @return string
	 */
	private function getJsOptions() {
		$jsOptions = array();

		/** Visualisation  */
		if (isset($this->settings['shape']) && $this->settings['shape'] != 'sphere' && trim($this->settings['shape']) != '') {
			$jsOptions['shape'] = 'shape: \'' . $this->settings['shape'] . '\'';
		}

		if (isset($this->settings['textColour']) && $this->settings['textColour'] != '#ff99ff'
			&& trim($this->settings['textColour']) != ''
		) {
			$jsOptions['textColour'] = 'textColour: \'' . $this->settings['textColour'] . '\'';
		}

		if (isset($this->settings['textHeight']) && $this->settings['textHeight'] != '15'
			&& trim($this->settings['textHeight']) != ''
		) {
			$jsOptions['textHeight'] = 'textHeight: \'' . $this->settings['textHeight'] . '\'';
		}

		if (isset($this->settings['txtOpt']) && $this->settings['txtOpt'] != 'true') {
			$jsOptions['txtOpt'] = 'txtOpt: ' . $this->settings['txtOpt'];
		}

		if (isset($this->settings['txtScale']) && $this->settings['txtScale'] != '2'
			&& trim($this->settings['txtScale']) != ''
		) {
			$jsOptions['txtScale'] = 'txtScale: ' . $this->settings['txtOpt'];
		}

		if (isset($this->settings['shadow']) && $this->settings['shadow'] != '#000000' && trim($this->settings['shadow']) != '') {
			$jsOptions['shadow'] = 'shadow: \'' . $this->settings['shadow'] . '\'';
		}

		if (isset($this->settings['shadowBlur']) && $this->settings['shadowBlur'] != '0'
			&& trim($this->settings['shadowBlur']) != ''
		) {
			$jsOptions['shadowBlur'] = 'shadowBlur: ' . $this->settings['shadowBlur'];
		}

		if (isset($this->settings['shadowOffset']) && $this->settings['shadowOffset'] != '[0,0]'
			&& trim($this->settings['shadowOffset']) != ''
		) {
			$jsOptions['shadowOffset'] = 'shadowOffset: ' . $this->settings['shadowOffset'];
		}

		if (isset($this->settings['outlineColour']) && $this->settings['outlineColour'] != '#ffff99'
			&& trim($this->settings['outlineColour']) != ''
		) {
			$jsOptions['outlineColour'] = 'outlineColour: \'' . $this->settings['outlineColour'] . '\'';
		}

		if (isset($this->settings['outlineMethod']) && $this->settings['outlineMethod'] != 'outline') {
			$jsOptions['outlineMethod'] = 'outlineMethod: \'' . $this->settings['outlineMethod'] . '\'';
		}

		if (isset($this->settings['outlineThickness']) && $this->settings['outlineThickness'] != '2'
			&& trim($this->settings['outlineThickness']) != ''
		) {
			$jsOptions['outlineThickness'] = 'outlineThickness: ' . $this->settings['outlineThickness'];
		}

		if (isset($this->settings['outlineOffset']) && $this->settings['outlineOffset'] != '5'
			&& trim($this->settings['outlineOffset']) != ''
		) {
			$jsOptions['outlineOffset'] = 'outlineOffset: ' . $this->settings['outlineOffset'];
		}

		if (isset($this->settings['maxBrightness']) && $this->settings['maxBrightness'] != '1.0'
			&& trim($this->settings['maxBrightness']) != ''
		) {
			$jsOptions['maxBrightnesst'] = 'maxBrightness: ' . $this->settings['maxBrightness'];
		}

		if (isset($this->settings['minBrightness']) && $this->settings['minBrightness'] != '0.1'
			&& trim($this->settings['minBrightness']) != ''
		) {
			$jsOptions['minBrightness'] = 'minBrightness: ' . $this->settings['minBrightness'];
		}

		/** Animation  */
		if (isset($this->settings['fadeIn']) && $this->settings['fadeIn'] != '0'
			&& trim($this->settings['fadeIn']) != ''
		) {
			$jsOptions['fadeIn'] = 'fadeIn: ' . $this->settings['fadeIn'];
		}

		if (isset($this->settings['initial']) && trim($this->settings['initial']) != '') {
			$jsOptions['initial'] = 'initial: ' . $this->settings['initial'];
		}

		if (isset($this->settings['animTiming']) && $this->settings['animTiming'] != 'Smooth') {
			$jsOptions['animTiming'] = 'animTiming: \'' . $this->settings['animTiming'] . '\'';
		}

		if (isset($this->settings['maxSpeed']) && $this->settings['maxSpeed'] != '0.05'
			&& trim($this->settings['maxSpeed']) != ''
		) {
			$jsOptions['maxSpeed'] = 'maxSpeed: ' . $this->settings['maxSpeed'];
		}

		if (isset($this->settings['minSpeed']) && $this->settings['minSpeed'] != '0.0'
			&& trim($this->settings['minSpeed']) != ''
		) {
			$jsOptions['minSpeed'] = 'minSpeed: ' . $this->settings['minSpeed'];
		}

		if (isset($this->settings['interval']) && $this->settings['interval'] != '20'
			&& trim($this->settings['interval']) != ''
		) {
			$jsOptions['interval'] = 'interval: ' . $this->settings['interval'];
		}

		if (isset($this->settings['depth']) && $this->settings['depth'] != '0.5'
			&& trim($this->settings['depth']) != ''
		) {
			$jsOptions['depth'] = 'depth: ' . $this->settings['depth'];
		}

		if (isset($this->settings['pulsateTo']) && $this->settings['pulsateTo'] != '1.0'
			&& trim($this->settings['pulsateTo']) != ''
		) {
			$jsOptions['pulsateTo'] = 'pulsateTo: ' . $this->settings['pulsateTo'];
		}

		if (isset($this->settings['pulsateTime']) && $this->settings['pulsateTime'] != '3'
			&& trim($this->settings['pulsateTime']) != ''
		) {
			$jsOptions['pulsateTime'] = 'pulsateTime: ' . $this->settings['pulsateTime'];
		}

		/** Weight */
		if (isset($this->settings['weightactive']) && $this->settings['weightactive'] != '0') {
			$jsOptions['weightactive'] = 'weight: true ';
			$jsOptions['weightFrom'] = 'weightFrom: \'data-weight\'';
		}

		if (isset($this->settings['weightMode']) && $this->settings['weightMode'] != 'size') {
			$jsOptions['weightMode'] = 'weightMode: \'' . $this->settings['weightMode'] . '\'';
		}

		if (isset($this->settings['weightSize']) && $this->settings['weightSize'] != '1.0'
			&& trim($this->settings['weightSize']) != ''
		) {
			$jsOptions['weightSize'] = 'weightSize: ' . $this->settings['weightSize'];
		}

		if (isset($this->settings['weightGradient'])
			&& $this->settings['weightGradient'] != "{0:'#f00', 0.33:'#ff0', 0.66:'#0f0', 1:'#00f'}"
			&& trim($this->settings['weightGradient']) != ''
		) {
			$jsOptions['weightGradient'] = 'weightGradient: ' . $this->settings['weightGradient'];
		}

		if (isset($this->settings['weightSizeMin']) && trim($this->settings['weightSizeMin']) != '') {
			$jsOptions['weightSizeMin'] = 'weightSizeMin: ' . $this->settings['weightSizeMin'];
		}

		if (isset($this->settings['weightSizeMax']) && trim($this->settings['weightSizeMax']) != '') {
			$jsOptions['weightSizeMax'] = 'weightSizeMax: ' . $this->settings['weightSizeMax'];
		}

		/** OffsetsAndRadius */
		if (isset($this->settings['radiusX']) && $this->settings['radiusX'] != '1'
			&& trim($this->settings['radiusX']) != ''
		) {
			$jsOptions['radiusX'] = 'radiusX: ' . $this->settings['radiusX'];
		}

		if (isset($this->settings['radiusY']) && $this->settings['radiusY'] != '1'
			&& trim($this->settings['radiusY']) != ''
		) {
			$jsOptions['radiusY'] = 'radiusY: ' . $this->settings['radiusY'];
		}

		if (isset($this->settings['radiusZ']) && $this->settings['radiusZ'] != '1'
			&& trim($this->settings['radiusZ']) != ''
		) {
			$jsOptions['radiusZ'] = 'radiusZ: ' . $this->settings['radiusZ'];
		}

		if (isset($this->settings['offsetX']) && $this->settings['offsetX'] != '0'
			&& trim($this->settings['offsetX']) != ''
		) {
			$jsOptions['offsetX'] = 'offsetX: ' . $this->settings['offsetX'];
		}

		if (isset($this->settings['offsetY']) && $this->settings['offsetY'] != '0'
			&& trim($this->settings['offsetY']) != ''
		) {
			$jsOptions['offsetY'] = 'offsetY: ' . $this->settings['offsetY'];
		}

		if (isset($this->settings['stretchX']) && $this->settings['stretchX'] != '1'
			&& trim($this->settings['stretchX']) != ''
		) {
			$jsOptions['stretchX'] = 'stretchX: ' . $this->settings['stretchX'];
		}

		if (isset($this->settings['stretchY']) && $this->settings['stretchY'] != '1'
			&& trim($this->settings['stretchY']) != ''
		) {
			$jsOptions['stretchY'] = 'stretchY: ' . $this->settings['stretchY'];
		}

		/** Usability */
		if (isset($this->settings['dragControl']) && $this->settings['dragControl'] != 'false') {
			$jsOptions['dragControl'] = 'dragControl: ' . $this->settings['dragControl'];
		}

		if (isset($this->settings['dragThreshold']) && $this->settings['dragThreshold'] != '4'
			&& trim($this->settings['dragThreshold']) != ''
		) {
			$jsOptions['dragThreshold'] = 'dragThreshold: ' . $this->settings['dragThreshold'];
		}

		if (isset($this->settings['decel']) && $this->settings['decel'] != '0.95'
			&& trim($this->settings['decel']) != ''
		) {
			$jsOptions['decel'] = 'decel: ' . $this->settings['decel'];
		}

		if (isset($this->settings['freezeActive']) && $this->settings['freezeActive'] != 'false') {
			$jsOptions['freezeActive'] = 'freezeActive: ' . $this->settings['freezeActive'];
		}

		if (isset($this->settings['freezeDecel']) && $this->settings['freezeDecel'] != 'false') {
			$jsOptions['freezeDecel'] = 'freezeDecel: ' . $this->settings['freezeDecel'];
		}

		if (isset($this->settings['frontSelect']) && $this->settings['frontSelect'] != 'false') {
			$jsOptions['frontSelect'] = 'frontSelect: ' . $this->settings['frontSelect'];
		}

		if (isset($this->settings['noSelect']) && $this->settings['noSelect'] != 'false') {
			$jsOptions['noSelect'] = 'noSelect: ' . $this->settings['noSelect'];
		}

		if (isset($this->settings['noMouse']) && $this->settings['noMouse'] != 'false') {
			$jsOptions['noMouse'] = 'noMouse: ' . $this->settings['noMouse'];
		}

		if (isset($this->settings['lock']) && trim($this->settings['lock']) != '') {
			$this->settings['lock'] = str_replace('"', '', $this->settings['lock']);
			$jsOptions['lock'] = 'lock: \'' . $this->settings['lock'] . '\'';
		}

		if (isset($this->settings['wheelZoom']) && $this->settings['wheelZoom'] != 'true') {
			$jsOptions['wheelZoom'] = 'wheelZoom: ' . $this->settings['wheelZoom'];
		}

		if (isset($this->settings['zoom']) && $this->settings['zoom'] != '1.0'
			&& trim($this->settings['zoom']) != ''
		) {
			$jsOptions['zoom'] = 'zoom: ' . $this->settings['zoom'];
		}

		if (isset($this->settings['zoomStep']) && $this->settings['zoomStep'] != '0.05'
			&& trim($this->settings['zoomStep']) != ''
		) {
			$jsOptions['zoomStep'] = 'zoomStep: ' . $this->settings['zoomStep'];
		}

		if (isset($this->settings['zoomMax']) && $this->settings['zoomMax'] != '3.0'
			&& trim($this->settings['zoomMax']) != ''
		) {
			$jsOptions['zoomMax'] = 'zoomMax: ' . $this->settings['zoomMax'];
		}

		if (isset($this->settings['zoomMin']) && $this->settings['zoomMin'] != '0.3'
			&& trim($this->settings['zoomMin']) != ''
		) {
			$jsOptions['zoomMin'] = 'zoomMin: ' . $this->settings['zoomMin'];
		}

		if (isset($this->settings['clickToFront']) && trim($this->settings['clickToFront']) != '') {
			$jsOptions['clickToFront'] = 'clickToFront: ' . $this->settings['clickToFront'];
		}

		if (isset($this->settings['reverse']) && $this->settings['reverse'] != 'false') {
			$jsOptions['reverse'] = 'reverse: ' . $this->settings['reverse'];
		}

		$jsOptionsStr = implode(",\n", $jsOptions);
		return $jsOptionsStr;
	}


}

?>