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

class UcoscanViewDomains extends JViewLegacy
{
	protected $items;

	protected $state;

	protected $pagination;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->state		= $this->get('State');
		$this->pagination	= $this->get('Pagination');

		UcoscanHelper::addSubmenu('domains');

		if (count($errors = $this->get('Errors')))
		{
			JFactory::getApplication()->enqueueMessage(implode("\n", $errors), 'error');
			return false;
		}

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$state	= $this->get('State');

		$canDo	= UcoscanHelper::getActions($state->get('filter.category_id'));

		$user	= JFactory::getUser();
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_UCOSCAN_MANAGER_DOMAINS'), '');

		if (count($user->getAuthorisedCategories('com_ucoscan', 'core.create')) > 0)
		{
			JToolbarHelper::addNew('domain.add');
		}

		if ($canDo->get('core.edit'))
		{
			JToolbarHelper::editList('domain.edit');
		}
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper::publish('domains.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('domains.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			JToolbarHelper::archiveList('domains.archive');
			JToolbarHelper::checkin('domains.checkin');
		}
		$state	= $this->get('State');
		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'domains.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('domains.trash');
		}
		if ($canDo->get('core.admin'))
		{
			JToolbarHelper::preferences('com_ucoscan');
		}

		JHtmlSidebar::setAction('index.php?option=com_ucoscan&view=domains');

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);
	}

	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.state' => JText::_('JSTATUS'),
                    	'a.title' => JText::_('COM_UCOSCAN_FIELD_DOMAIN_LABEL'),
  			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
                                           
          