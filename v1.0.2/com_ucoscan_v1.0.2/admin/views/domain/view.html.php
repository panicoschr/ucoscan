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

class UcoscanViewDomain extends JViewLegacy
{
	protected $item;

	protected $form;

	public function display($tpl = null)
	{
            
                JHtml::script(JURI::root() . 'media/com_ucoscan/js/validate.js', true);
                JHtml::script(JURI::root() . 'media/com_ucoscan/js/jquery-1.11.3.min.js', true);
                JHtml::script(JURI::root() . 'media/com_ucoscan/js/jquery.maskedinput.js', true);
          
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$canDo		= UcoscanHelper::getActions($this->item->catid, 0);

		JToolbarHelper::title(JText::_('COM_UCOSCAN_MANAGER_DOMAIN'), '');

		if ($canDo->get('core.edit')||(count($user->getAuthorisedCategories('com_ucoscan', 'core.create'))))
		{
			JToolbarHelper::apply('domain.apply');
			JToolbarHelper::save('domain.save');
		}
		if (count($user->getAuthorisedCategories('com_ucoscan', 'core.create'))){
			JToolbarHelper::save2new('domain.save2new');
		}
		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_ucoscan', 'core.create')) > 0))
		{
			JToolbarHelper::save2copy('domain.save2copy');
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('domain.cancel');
		}
		else
		{
			JToolbarHelper::cancel('domain.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}