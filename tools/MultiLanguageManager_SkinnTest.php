<?php
/*
 * Created on 27 mai 2007
 *
 * http://www.art122-5.net
 * Marc Despland 
 * 
 * This file is used to test a skin
 * It needs phpunit to be installed
 * http://www.art122-5.net/index.php/PHP_Development_Tools
 * 
 * And you have to put it in the root directory of MediaWiki
 * 
 * $tgSkinToTest define the skin to test, but that test only work for the default one
 * 
 * Documentation about installation, how to use or modification could be found at :
 * http://www.art122-5.net/index.php/MultiLanguageManager_Extension
 *
 * Copyright (C) 2007  Marc Despland
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
 */
// Call MultiLanguageManagerTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "MultiLanguageManager_SkinTest::main");
}

$tgSkinToTest="Default";

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

include_once( dirname(__FILE__).'/includes/WebStart.php' );
if (defined(MEDIAWIKI)) {
	# Initialize MediaWiki base class
	require_once( dirname(__FILE__).'/includes/Wiki.php' );
	$mediaWiki = new MediaWiki();
} else {
	define( 'MEDIAWIKI', true );

	# Load up some global defines.
	require_once( './includes/Defines.php' );
	# Include this site setttings
	require_once( './LocalSettings.php' );
	# Prepare MediaWiki
	require_once( 'includes/Setup.php' );

	# Initialize MediaWiki base class
	require_once( "includes/Wiki.php" );
	$mediaWiki = new MediaWiki();
}

require_once( 'extensions/MultiLanguageManager/MultiLanguageManager.i18n.php' );

require_once( 'extensions/MultiLanguageManager/skins/'.$tgSkinToTest.'.php' );

/**
 * Test class for MultiLanguageManager_manager.
 * Generated by PHPUnit_Util_Skeleton on 2007-05-30 at 20:45:26.
 */
class MultiLanguageManager_SkinTest extends PHPUnit_Framework_TestCase {
	
	
	
	protected $configurationLoaded=FALSE;
	protected $notDefaultLanguage="fr";
	protected $invalidLanguage="aa";
	protected $dbw=NULL;
	protected $db_page_translation,$db_page, $db_page_language;
	protected $pageIdNotExists;
	protected $pageswithoutlanguage=array();
	
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
    	global $tgSkinToTest;
        require_once "PHPUnit/TextUI/TestRunner.php";
		echo "Start test for the skin " . $tgSkinToTest . "\n";
        $suite  = new PHPUnit_Framework_TestSuite("MultiLanguageManager_SkinTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
    	global $mgAvailableLanguage;
    	global $wgLanguageCode;
    	
    	$mgAvailableLanguage['en'] = 'English';
		$mgAvailableLanguage['fr'] = 'Français';
		$mgAvailableLanguage['de'] = 'Deutsch';

		$wgLanguageCode='en';
    	
    	//Define the parameter
    	$keys=array_keys($mgAvailableLanguage);
	    $i=0;
	    while (($i<count($keys)) && ($keys[$i]==$wgLanguageCode)) $i++;
	    if ($i<count($keys)) {
	    	$this->notDefaultLanguage=$keys[$i];
	    }
	    while (array_key_exists($this->invalidLanguage,$mgAvailableLanguage)) $this->invalidLanguage.='a';
		$this->dbw = wfGetDB( DB_MASTER );
 		$this->db_page=$this->dbw->tableName('page');
		$this->db_page_translation=$this->dbw->tableName('page_translation');
		$this->db_page_language  = $this->dbw->tableName('page_language');

		$i=1;
		$SQL="SELECT page_id FROM ! WHERE page_id = ?";
		$tbs=$this->dbw->safeQuery($SQL,$this->db_page,$i);
		while ($this->dbw->numRows($tbs)>0) {
			$i++;
			$tbs=$this->dbw->safeQuery($SQL,$this->db_page,$i);
		}
		$this->pageIdNotExists=$i;
		$SQL="SELECT p.page_id FROM (! p LEFT JOIN ! l ON p.page_id=l.page_id) LEFT JOIN ! t ON p.page_id=t.source WHERE l.page_id IS NULL and t.source IS NULL and p.page_namespace=0";
		$tbs=$this->dbw->safeQuery($SQL,$this->db_page,$this->db_page_language,$this->db_page_translation);
		for ($i=0;$i<(min(5,$this->dbw->numRows($tbs)));$i++) {
			$row = $this->dbw->fetchObject($tbs);
			$this->pageswithoutlanguage[$i]=$row->page_id;
		}
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    	for ($i=0;$i<5;$i++) {
    		$SQL="DELETE FROM ! WHERE page_id=?";
			$tbs=$this->dbw->safeQuery($SQL,$this->db_page_language,$this->pageswithoutlanguage[$i]);
			$SQL="DELETE FROM ! WHERE translate=? or source=?";
			$tbs=$this->dbw->safeQuery($SQL,$this->db_page_translation,$this->pageswithoutlanguage[$i],$this->pageswithoutlanguage[$i]);
    	}
    }
	
    /**
     * TARGET : displayExecuteResult
     */
    function testDisplayExecuteResult() {
    	$display=new MultiLanguageManager_display();
    	//echo $display->displayExecuteResult();
    	$display->displayExecuteResult();
    	$display->displayExecuteResult("");
    	$display->displayExecuteResult(NULL);
    	$display->displayExecuteResult("multilanguagemanager_data");
    	$this->assertTrue(TRUE);
    }
    /**
     * TARGET : displayExecuteError
     */
    function testDisplayExecuteError() {
    	$display=new MultiLanguageManager_display();
    	$display->displayExecuteError();
    	$display->displayExecuteError("");
    	$display->displayExecuteError(NULL);
    	$display->displayExecuteError("multilanguagemanager_data");
    	$this->assertTrue(TRUE);
    }
    
    /**
     * TARGET : displayLanguagePolicy
     */
    function testDisplayLanguagePolicy() {
    	global $mgImageUrl;
    	$display=new MultiLanguageManager_display();
    	$display->displayLanguagePolicy();
    	$this->assertTrue(TRUE);
     }
    
    /**
     * TARGET : displayItTranslates($list,$pageid ,$allowed)
     */
    function testDisplayItTranslates($list,$pageid ,$allowed) {
    	global $mgImageUrl;
    	global $wgScriptPath;
		$display=new MultiLanguageManager_display();
    	$pageA=Title::newFromId($this->pageswithoutlanguage[0]);
    	$pageB=Title::newFromId($this->pageswithoutlanguage[1]);
     	$display->displayItTranslates(array(),$this->pageswithoutlanguage[0] ,FALSE);
    	$display->displayItTranslates(array(),$this->pageswithoutlanguage[0] ,TRUE);
    	$display->displayItTranslates(array($this->pageswithoutlanguage[1]),$this->pageswithoutlanguage[0] ,TRUE);
    	$this->assertTrue(TRUE);
    }
    
    
    /**
     * TARGET : displayItIsTranslatedBy($list,$pageid ,$allowed)
     */
    function testDisplayItIsTranslatedBy() {
    	global $mgImageUrl;
		global $wgScriptPath;
		$display=new MultiLanguageManager_display();
    	$pageA=Title::newFromId($this->pageswithoutlanguage[0]);
    	$pageB=Title::newFromId($this->pageswithoutlanguage[1]);
     	$display->displayItIsTranslatedBy(array(),$this->pageswithoutlanguage[0] ,FALSE);
    	$display->displayItIsTranslatedBy(array(),$this->pageswithoutlanguage[0] ,TRUE);
    	$display->displayItIsTranslatedBy(array(array('pageid' => $this->pageswithoutlanguage[1], 'lang' => 'fr')),$this->pageswithoutlanguage[0] ,TRUE);
    	$this->assertTrue(TRUE);
    }

    /**
     * TARGET : displayChooseLanguage($language,$targetid ,$allowed,$hasDep)
     */
    function testDisplayChooseLanguage() {
    	global $mgImageUrl;
		$display=new MultiLanguageManager_display();
    	$pageA=Title::newFromId($this->pageswithoutlanguage[0]);
    	$pageB=Title::newFromId($this->pageswithoutlanguage[1]);
     	$display->displayChooseLanguage('fr',$this->pageswithoutlanguage[1] ,FALSE,TRUE);
    	$display->displayChooseLanguage('fr',$this->pageswithoutlanguage[1] ,TRUE,TRUE);
    	$display->displayChooseLanguage('fr',$this->pageswithoutlanguage[1] ,TRUE,FALSE);
    	$this->assertTrue(TRUE);
    }

    /**
     * TARGET : displayListTranslation($languageCode,$translationList)
     */

	public function testDisplayListTranslation() {
		global $mgImageUrl;
		$display=new MultiLanguageManager_display();
    	$pageA=Title::newFromId($this->pageswithoutlanguage[0]);
    	$pageB=Title::newFromId($this->pageswithoutlanguage[1]);

    	$display->displayListTranslation('fr',array());
     	$display->displayListTranslation('fr',array($this->pageswithoutlanguage[1]));
		$display->displayListTranslation('fr',array($this->pageswithoutlanguage[0],$this->pageswithoutlanguage[1]));
		$this->assertTrue(TRUE);
	}

    /**
     * TARGET : displayLanguageElement($language_code)
     */
	public function testDisplayLanguageElement() {
		global $wgLanguageCode;
		global $mgImageUrl;
		$display=new MultiLanguageManager_display();
    	$display->displayLanguageElement();
     	$display->displayLanguageElement($this->invalidLanguage);
		$display->displayLanguageElement($wgLanguageCode);
		$this->assertTrue(TRUE);
	}

    /**
     * TARGET : displayIcon($language_code)
     */
	public function testDisplayIcon() {
		global $wgLanguageCode;
		global $mgImageUrl;
		$display=new MultiLanguageManager_display();
    	$display->displayIcon();
     	$display->displayIcon($this->invalidLanguage);
		$display->displayIcon($wgLanguageCode);
		$this->assertTrue(TRUE);
	}
    /**
     * TARGET : displayDirectPageLink($displayLanguage,$translationId)
     */
	public function testDisplayDirectPageLink() {
		global $wgLanguageCode;
		$display=new MultiLanguageManager_display();
		$pageB=Title::newFromId($this->pageswithoutlanguage[1]);
    	$display->displayDirectPageLink();
     	$display->displayDirectPageLink("",$this->invalidLanguage);
		$display->displayDirectPageLink("data",$this->pageswithoutlanguage[1]);
		$this->assertTrue(TRUE);
	} 
	
	
    /**
     * TARGET : displayLanguageLinkPopup($visibleLinkText,$contentLinkTest)
     */
	public function testDisplayLanguageLinkPopup() {
		global $wgLanguageCode;
		$display=new MultiLanguageManager_display();
    	$display->displayLanguageLinkPopup("le lien", "Le contenu");
    	$this->assertTrue(TRUE);
	} 
 
}
// Call MultiLanguageManagerTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "MultiLanguageManager_SkinTest::main") {
    MultiLanguageManager_SkinTest::main();
}
?>
