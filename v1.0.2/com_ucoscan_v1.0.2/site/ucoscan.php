<?php
/**
 * @package	UCOSCAN
 * @subpackage	Components
 * @copyright	WWW.MEPRO.CO - All rights reserved.
 * @author	MEPRO SOFTWARE SOLUTIONS
 * @link	http://www.mepro.co
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

// No direct access
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$cssFile = "./media/com_ucoscan/css/site.stylesheet.css";
$document->addStyleSheet($cssFile);

$controller	= JControllerLegacy::getInstance('Ucoscan');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();