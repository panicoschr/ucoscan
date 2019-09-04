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

class UcoscanController extends JControllerLegacy
{
	protected $default_view = 'ucoscansites';

	public function display($cachable = false, $urlparams = false)
	{
            
            
		require_once JPATH_COMPONENT.'/helpers/ucoscan.php';

		$view   = $this->input->get('view', 'ucoscansites');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

		if ($view == 'cpanel' && $layout == 'edit' && !$this->checkEditId('com_ucoscan.edit.ucoscansite', $id))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_ucoscan&view=ucoscansites', false));

			return false;
		}

		parent::display();

		return $this;
	}
}