<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "customnewstagcloud"
 *
 * Auto generated by Extension Builder 2014-02-19
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Custom tt_news Tagcloud',
	'description' => 'A javascript tagcloud for tt_news categories - uses tagCanvas as javascript library.',
	'category' => 'plugin',
	'author' => 'Pierre Arlt',
	'author_email' => 'info@pierrearlt.com',
	'author_company' => 'Grafik, Entwicklung & IT Service | Pierre Arlt',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.0.10',
	'constraints' => array(
		'depends' => array(
			'extbase' => '6.0',
			'fluid' => '6.0',
			'typo3' => '6.0.0-6.2.99',
			'tt_news' => '',
		),
		'conflicts' => array(),
		'suggests' => array(),
	)
);

?>