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

class UcoscanModelUcoscansite extends JModelAdmin {

    protected $text_prefix = 'COM_UCOSCAN';

    protected function canDelete($record) {
        if (!empty($record->id)) {
            if ($record->state != -2) {
                return;
            }
            $user = JFactory::getUser();

            if ($record->catid) {
                return $user->authorise('core.delete', 'com_ucoscan.category.' . (int) $record->catid);
            } else {
                return parent::canDelete($record);
            }
        }
    }

    protected function canEditState($record) {
        $user = JFactory::getUser();

        if (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_ucoscan.category.' . (int) $record->catid);
        } else {
            return parent::canEditState($record);
        }
    }

    public function getTable($type = 'Ucoscansite', $prefix = 'UcoscanTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $app = JFactory::getApplication();

        $form = $this->loadForm('com_ucoscan.ucoscansite', 'ucoscansite', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_ucoscan.edit.ucoscansite.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    protected function prepareTable($table) {
        $table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
        $user = JFactory::getUser();
        $user_id = $user->id;
        $table->created_by = $user_id;
    }

    public function check_No_Of_Chars($data) {

        $no_of_chars = $data['no_of_chars'];
        $domainandid = $data['domainandid'];
        $domainpos = strpos($domainandid, "the id is");
        $domain = substr($domainandid, 0, $domainpos);


        $suffixandid = $data['suffixandid'];
        $suffixpos = strpos($suffixandid, "the id is");
        $suffix = substr($suffixandid, 0, $suffixpos);

        $array_domain = explode(" ", $domain);

        $legth_of_domain = sizeof($array_domain);

        if (($no_of_chars > $legth_of_domain) || ($no_of_chars < 1)) {
            return false;
        } else {
            return true;
        }
    }

    public function check_cms_selection($data) {

        $cms_selection = $data['cms_list'];
        $no_of_chars = $data['no_of_chars'];
        $found = false;
        for ($i = 0; $i <= $no_of_chars; $i++) {
            if (count($cms_selection) > 0) {
                $found = true;
            }
            return $found;
        }
    }

    
    public function check_domain_selection($data) {

        $domainandid = $data['domainandid'];
        if (strlen($domainandid) > 0) {
            return true;
        }else
        {
            return false;
        }
        
    }        
    
    
    public function check_suffix_selection($data) {

        $suffixandid = $data['suffixandid'];
        if (strlen($suffixandid) > 0) {
            return true;
        }else
        {
            return false;
        }
        
    }       
    
    public function constructUrls($data) {

        $db = JFactory::getDbo();
        $process_array = array();
        $domainandid = $data['domainandid'];
        $suffixandid = $data['suffixandid'];
        $cms_list = $data['cms_list'];
        $cms_list_string = '';
        $cms_list_all = $data['cms_list_all'];
        $i = 0;


        $user = JFactory::getUser();
        $user_id = $user->id;



        foreach ($cms_list as $cms_list_value) {
            if ($i == 0) {
                $cms_list_string = $cms_list_value;
            } else {
                $cms_list_string = $cms_list_string . ' ' . $cms_list_value;
            }
            $i++;
        }
        $catid = $data['catid'];

        $domainpos = strpos($domainandid, "the id is");
        $domain_parent_id = substr($domainandid, $domainpos + 9);
        $domain = substr($domainandid, 0, $domainpos);

        $suffixpos = strpos($suffixandid, "the id is");
        $suffix_parent_id = substr($suffixandid, $suffixpos + 9);
        $suffix = substr($suffixandid, 0, $suffixpos);

        $state = 0;

        $no_of_chars = $data['no_of_chars'];

        $count_no_of_chars = 0;
        $array_domain = explode(" ", $domain);
        sort($array_domain);
        
     $domain_length = count($array_domain);        
        
        $myword = '';
        $url = '';
        $x = 0;


        $querydelete = $db->getQuery(true);
        $deleteconditions = array(
            $db->quoteName('created_by') . ' = ' . "'$user_id'"
        );

        $querydelete->delete($db->quoteName('#__ucoscan_ucoscansite'));
        $querydelete->where($deleteconditions);

        $db->setQuery($querydelete);

        $resultdelete = $db->execute();




/*
        $queryinsert = $db->getQuery(true);
        $queryinsert->insert($db->quoteName('#__ucoscan_ucoscansite'));
        $insertcolumns = array('url', 'catid', 'domain_parent_id', 'suffix_parent_id', 'state', 'cms_list', 'cms_list_all', 'created_by');
        $queryinsert->columns($db->quoteName($insertcolumns));
*/

   //     $sorted_array_domain = implode('', $array_domain);
   //     $domain_length = strlen($sorted_array_domain);
        for ($i = 0; $i < $no_of_chars; $i++) {
            $process_array[$i] = $array_domain[0];
        }

        /*
          $query_select_suffix = $db->getQuery(true);
          $query_select_suffix->select($db->quoteName(array('id', 'title')));
          $query_select_suffix->from($db->quoteName('#__ucoscan_suffix'));
          $query_select_suffix->where('(state IN (1))');
          $db->setQuery($query_select_suffix);
          $suffixes_results = $db->loadObjectList();
         */
        while ($x < pow($domain_length, $no_of_chars)) {
            While ($count_no_of_chars < $no_of_chars) {
                $process_array[$no_of_chars - $count_no_of_chars] = (int) fmod($x / pow($domain_length, $count_no_of_chars), $domain_length);
                $myword = $myword . $array_domain[$process_array[$no_of_chars - $count_no_of_chars]];
                $count_no_of_chars++;
            }

            //     foreach ($suffixes_results as $suffixes_result) {
            //   $suffix_parent_id = $suffixes_result->id;
            $suffix_title = $suffix;




            $url = "http://www." . $myword . "." . $suffix_title;
            
        $queryinsert = $db->getQuery(true);
        $queryinsert->insert($db->quoteName('#__ucoscan_ucoscansite'));
        $insertcolumns = array('url', 'catid', 'domain_parent_id', 'suffix_parent_id', 'state', 'cms_list', 'cms_list_all', 'created_by');
        $queryinsert->columns($db->quoteName($insertcolumns));
        $values = array("'$url'", $catid, $domain_parent_id, $suffix_parent_id, $state, "'$cms_list_string'", "'$cms_list_all'", "'$user_id'");
        $queryinsert->values(implode(',', $values));
        $db->setQuery($queryinsert);
        $db->execute();            
            //     }

            $myword = '';
            $count_no_of_chars = 0;
            $x++;
        }
 //       $db->setQuery($queryinsert);
  //      $db->execute();


        return true;
    }

}
