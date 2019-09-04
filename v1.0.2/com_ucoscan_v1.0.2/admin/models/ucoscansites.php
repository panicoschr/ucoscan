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
class UcoscanModelUcoscansites extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
                                'created_by', 'a.created_by',
				'state', 'a.state',
                                'domain_parent_id', 'a.domain_parent_id',
                                'detecteucoscan', 'a.detecteucoscan',
                                'cms_list', 'a.cms_list',
				'company', 'a.company',
				'url', 'a.url',                            
                                'description', 'a.description',        
                                'dtitle', 'd.title',    
                                'stitle', 's.title',   
			
				
				'publish_down', 'a.publish_down',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'catid', 'a.catid', 'category_title'
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);

		parent::populateState('a.ordering', 'asc');
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
  //  $user = JFactory::getUser();                
  // $userid = $user->id;
$userid = '';
         
     $now = new DateTime();
     $nowstring=$now->format('Y-m-d');   

		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.catid, a.created_by, ' .
				'a.state, a.company, a.description, a.domain_parent_id, a.suffix_parent_id, a.cms_list, a.detecteucoscan, ' .
                                'a.url, ' .
                                            
                  
                                
				'a.featured, a.publish_up, a.publish_down, a.ordering'
			)
		);
		$query->from($db->quoteName('#__ucoscan_ucoscansite').' AS a');

		$published = $this->getState('filter.state');
		if (is_numeric($published))
		{
			$query->where('a.state = '.(int) $published);
		} elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}
                
$query->join('LEFT', $db->quoteName('#__ucoscan_domain', 'd') . ' ON (' . $db->quoteName('d.id') . ' = ' . $db->quoteName('a.domain_parent_id') . ')');
$query->join('LEFT', $db->quoteName('#__ucoscan_suffix', 's') . ' ON (' . $db->quoteName('s.id') . ' = ' . $db->quoteName('a.suffix_parent_id') . ')');                 
               
                

$query->select('d.title AS dtitle');
$query->select('s.title AS stitle');
     
$query->where('a.url != '. "''");       

$query->where('a.created_by = '. "'$userid'"); 

//$query->where('a.created_by = '. "'$userid'");  
		// Join over the categories.

     
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.detecteucoscan LIKE '.$search.' OR a.url LIKE '.$search.')');
			}
		}

		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering')
		{
			$orderCol = 'a.title '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
}