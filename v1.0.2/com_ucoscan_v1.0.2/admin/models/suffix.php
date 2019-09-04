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

class UcoscanModelSuffix extends JModelAdmin
{
	protected $text_prefix = 'COM_UCOSCAN';

public function delete($pks)
     {
	$pks = (array) $pks;
        JArrayHelper::toInteger($pks);

 
        if (empty($pks))
		{
			$this->setError(JText::_('COM_UCOSCAN_NO_ITEM_SELECTED'));
			return false;
		}

		try
		{
			$db = $this->getDbo();

			$db->setQuery(
				'DELETE FROM #__ucoscan_ucoscansite' .
					' WHERE suffix_parent_id IN (' . implode(',', $pks) . ')'
			);
			$db->execute();

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		$this->cleanCache();

                
parent::delete($pks);

		return true;    
       }          
        
        
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return;
			}
			$user = JFactory::getUser();

			if ($record->catid)
			{
				return $user->authorise('core.delete', 'com_ucoscan.category.'.(int) $record->catid);
			}
			else
			{
				return parent::canDelete($record);
			}
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_ucoscan.category.'.(int) $record->catid);
		}
		else
		{
			return parent::canEditState($record);
		}
	}

	public function getTable($type = 'Suffix', $prefix = 'UcoscanTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();

		$form = $this->loadForm('com_ucoscan.suffix', 'suffix', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_ucoscan.edit.suffix.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	protected function prepareTable($table)
	{

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);

	}

        
  
 

               
        
        
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks))
		{
			$this->setError(JText::_('COM_UCOSCAN_NO_ITEM_SELECTED'));
			return false;
		}

		try
		{
			$db = $this->getDbo();

			$db->setQuery(
				'UPDATE #__ucoscan_suffix' .
					' SET featured = ' . (int) $value .
					' WHERE id IN (' . implode(',', $pks) . ')'
			);
			$db->execute();

		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		$this->cleanCache();

		return true;
	}

	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Convert the metadata field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_ucoscan.suffix');
				$item->metadata['tags'] = $item->tags;
			}
		}

		return $item;
	}
}