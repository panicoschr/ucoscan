<?php 
/**
 * @package	HR
 * @subpackage	Components
 * @copyright	WWW.MEPRO.CO - All rights reserved.
 * @author	MEPRO SOFTWARE SOLUTIONS
 * @link	http://www.mepro.co
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
  
defined('_JEXEC') or die;
jimport('joomla.form.helper');
jimport('joomla.form.formfield');
jimport('joomla.html.html');
JFormHelper::loadFieldClass('List');


//test

  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldMysuffix extends JFormFieldList
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'Mysuffix'; 
  
     /** 
      * Overrides parent's getinput method 
      * 
      */ 
     
     
     
     
     
     
     
     
     
 protected function getOptions()
	{
		// Get the database object and a new query object.
		
		// Build the query.
    $db =& JFactory::getDbo(); 
         
    //    require_once JPATH_COMPONENT.'/helpers/hr.php';
                
  $user = JFactory::getUser();       

        $userid = $user->id;
 $stateid = 1;

        

   

$fieldlistsuffixs = array('d.id', 'd.title'); // add the field names to an array
 $fieldlistsuffixs[0] = 'distinct ' . $fieldlistsuffixs[0]; //prepend the distinct keyword to the first field name  

 
$querymysuffix  = $db->getQuery(true);
$querymysuffix->select($fieldlistsuffixs);
$querymysuffix->from($db->quoteName('#__ucoscan_suffix', 'd'));
$querymysuffix->where('d.created_by = '. "'$userid'");  
$querymysuffix->where('d.state = '. "'$stateid'");    
$querymysuffix->order($db->quoteName('d.title'));




		// Set the query and load the options.
		$db->setQuery($querymysuffix);
		$options = $db->loadObjectList();
		$lang = JFactory::getLanguage();
                
   //$i=1;
           
            
                
		foreach ($options as $i=>$option) {
			$options[$i]->value = JText::_($option->title).'the id is'.JText::_($option->id);
                        $options[$i]->text = JText::_($option->title);
		}

		// Check for a database error.
		if ($db->getErrorNum()) {
			JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'warning');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

	return $options;
	}    
     
     
 }  
     
     
     
      ?> 
     
     
     
     
     
     