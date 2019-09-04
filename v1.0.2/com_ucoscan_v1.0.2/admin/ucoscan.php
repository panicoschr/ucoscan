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

if (!JFactory::getUser()->authorise('core.manage', 'com_ucoscan'))
{
	return JFactory::getApplication()->enqueueMessage(JText::_('COM_UCOSCAN_ERROR_ALERTNOAUTHOR'), 'warning');
}

$controller	= JControllerLegacy::getInstance('Ucoscan');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();