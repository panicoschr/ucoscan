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

/**
 * Script file of fs component
 */
class com_ucoscanInstallerScript {

    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent) {
        // $parent is the class calling this method
        //redirect to component
        $parent->getParent()->setRedirectURL('index.php?option=com_ucoscan');
    }

    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent) {
        // $parent is the class calling this method
        //echo '<p>' . JText::_('COM_SPDIGITALGOODIRECTORY_SEPARATOR_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent) {
        // $parent is the class calling this method
        //echo '<p>' . JText::_('COM_SPDIGITALGOODIRECTORY_SEPARATOR_UPDATE_TEXT') . '</p>';
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        //echo '<p>' . JText::_('COM_SPDIGITALGOODIRECTORY_SEPARATOR_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent) {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        //echo '<p>' . JText::_('COM_SPDIGITALGOODIRECTORY_SEPARATOR_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
        //Add initial directory
        $jAp = & JFactory::getApplication();
        JTable::addIncludePath(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_ucoscan' . DIRECTORY_SEPARATOR . 'tables');

        //prepare categories
        if(!$this->prepareCaregories($parent)) 
            return true;
        
        // Clear the component's cache
        $cache = JFactory::getCache('com_ucoscan');
        $cache->clean();

        //Update client_id in extensions table to enable online upgrade
        $db = & JFactory::getDBO();
        
        $query = $db->getQuery(true);

        $fields_to_update = array(
            $db->quoteName('client_id') . ' = ' .  $db->quote('0') 
        );        
        $conditions_to_update = array(
            $db->quoteName('name') . ' = ' . "'com_ucoscan'"
        );        

        

        
        $query->update($db->quoteName('#__extensions'))->set($fields_to_update)->where($conditions_to_update);        
        
        
        
   //     $query = "UPDATE '#__extensions' SET 'client_id' = 0  WHERE name =com_ucoscan;";
        $db->setQuery($query);
        
        if (!$db->query()) {
            $jAp->enqueueMessage(nl2br($db->getErrorMsg()), 'error');
            return;
        }
        
        //Activate plugins
        /*
        $query = 'UPDATE ' . $db->quoteName('#__extensions');
        $query .= ' SET 'enabled' = ' . $db->quote('1');
        $query .= ' WHERE ' . $db->quoteName('element') . ' LIKE ' . $db->quote('fsaddintransaction');
        $db->setQuery($query);
        if (!$db->query())
            JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'error');
        
        $query = 'UPDATE ' . $db->quoteName('#__extensions');
        $query .= ' SET 'enabled' = ' . $db->quote('1');
        $query .= ' WHERE ' . $db->quoteName('element') . ' LIKE ' . $db->quote('fsshowintransaction');
        $db->setQuery($query);
        if (!$db->query())
            JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'error');
         * 
         */
    }
    
    private $father_id;
    private $grandfather_id;
    protected function saveCategory($data, $parentCategory = 'child', $parent) {

        $table = & JTable::getInstance('Category');

        switch ($parentCategory) {
            case 'grandfather':
                $data['parent_id'] = 1;
                break;
            case 'father':
                $data['parent_id'] = $this->grandfather_id;
                break;
            default:
                $data['parent_id'] = $this->father_id;
                break;
        }

        $data['id'] = 0;
        
        // Set the new parent id if parent id not matched OR while New/Save as Copy .
        if ($table->parent_id != $data['parent_id'] || $data['id'] == 0) {
            $table->setLocation($data['parent_id'], 'last-child');
        }

        // Bind the data.
        if (!$table->bind($data)) {
            $parent->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check()) {
            $parent->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $parent->setError($table->getError());
            return false;
        }

        // Rebuild the path for the category:
        if (!$table->rebuildPath($table->id)) {
            $parent->setError($table->getError());
            return false;
        }

        // Rebuild the paths of the category's children:
        if (!$table->rebuild($table->id, $table->lft, $table->level, $table->path)) {
            $parent->setError($table->getError());
            return false;
        }

        if ($parentCategory == 'grandfather') {
            $this->grandfather_id = $table->id;
        }
        if ($parentCategory == 'father') {
            $this->father_id = $table->id;
        }

        return true;
    }
    
    protected function prepareCaregories($parent) {

        //assign values
        $data = array();
        $data['level'] = 1;
        $data['extension'] = 'com_ucoscan';
        $data['published'] = 1;
        $data['access'] = 2;
        $data['params'] = '{"target":"","image":""}';
        $data['metadata'] = '{"page_title":"","author":"","robots":"","tags":""}';
        $data['language'] = '*';

	 $data['title'] = 'Suffixes';
        if (!$this->saveCategory($data, 'grandfather', $parent))
            return false;
        
           $data['title'] = 'Domains';
        if (!$this->saveCategory($data, 'grandfather', $parent))
            return false;         
              
		
	 $data['title'] = 'Ucoscansites';
        if (!$this->saveCategory($data, 'grandfather', $parent))
            return false;         
 
        return true;

    }

}
