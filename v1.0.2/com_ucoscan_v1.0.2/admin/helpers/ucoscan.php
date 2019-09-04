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

class UcoscanHelper {

    function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = str_replace("<head>", "<head><base href=\"$url\">", $data);
        return $data;
    }

    function cmsDetect($pks) {


        $result = '';
        $found = false;
        $value = '';
        $list_of_ids_to_delete = '';

        $db = JFactory::getDbo();
        $querydelete = $db->getQuery(true);
       
        $array_of_ids_to_delete = array();

        
        
        
        $queryselecturlandcmslist = $db->getQuery(true);
        $queryselecturlandcmslist
                ->select($db->quoteName(array('uss.id', 'uss.url', 'uss.cms_list', 'uss.cms_list_all')))
                ->from($db->quoteName('#__ucoscan_ucoscansite', 'uss'))
                ->where($db->quoteName('uss.id') . ' IN  (' . implode(',', $pks) . ')');
        $db->setQuery($queryselecturlandcmslist);



        $queryselecturlandcmslists = $db->loadObjectList();

        foreach ($queryselecturlandcmslists as $queryselecturlandcmslist) {
                  $result = '';
                  $found = false;
                  $value = '';
            
            $id = $queryselecturlandcmslist->id;
            $url = $queryselecturlandcmslist->url;
            $cms_list_string = $queryselecturlandcmslist->cms_list;
            $cms_list_all = $queryselecturlandcmslist->cms_list_all;
            $cms_list = explode(" ", $cms_list_string);
         
            


            foreach ($cms_list as $value) {
                if ($found == true) {
                    break;
                }

                $queryselectcms = $db->getQuery(true);


                $queryselectcms
                        ->select($db->quoteName(array('uc.title', 'uc.concat_url', 'uc.search_string')))
                        ->from($db->quoteName('#__ucoscan_cms', 'uc'))
                        ->where($db->quoteName('uc.title') . ' =  ' . "'$value'");

                $db->setQuery($queryselectcms);
                $queryselectcmss = $db->loadObjectList();


                foreach ($queryselectcmss as $queryselectcms) {
                    if ($found == true) {
                       break;
                    }
                    $concat_url = $queryselectcms->concat_url;
                    $search_string = $queryselectcms->search_string;
                    $concatenated_url = $url . $concat_url;

                       $postdata = http_build_query(
                                array(
                                    'var1' => 'some content',
                                    'var2' => 'doh'
                                )
                        );

                        $opts = array('http' =>
                            array(
                                'method' => 'POST',
                                'header' => 'Content-type: application/x-www-form-urlencoded',
                                'content' => $postdata
                            )
                        );

                        $context = stream_context_create($opts);

                        $data_extended = file_get_contents($concatenated_url, false, $context);

   if (strpos(strtolower($data_extended), strtolower($search_string)) != false)  {
                                $result = $value;
                                $found = true;
 $queryupdatecms = $db->getQuery(true);

                                $fieldsupdate = array(
                                    $db->quoteName('detecteucoscan') . ' = ' . "'$result'"
                                );
                                $conditionsupdate = array(
                                    $db->quoteName('id') . ' = ' . $id
                                );
$queryupdatecms->update($db->quoteName('#__ucoscan_ucoscansite'))->set($fieldsupdate)->where($conditionsupdate);
 $db->setQuery($queryupdatecms);
$updatecms=$db->execute();                                 
                               
                           
                        }
              }
           }
            
                     if (($found == false) && ($cms_list_all == 'os')) {
                                   $array_of_ids_to_delete[] = $id;
                                }          
 
        }

        $string_array_of_ids_to_delete = implode("' , '", $array_of_ids_to_delete);
$conditionsdelete = 
    $db->quoteName('id') . ' IN (' . '\'' . "$string_array_of_ids_to_delete" . '\'' . ')';

 
$querydelete->delete($db->quoteName('#__ucoscan_ucoscansite'));
$querydelete->where($conditionsdelete);
 $db->setQuery($querydelete);
$deleteids = $db->execute();       
    

                             
 
 





                                   
        return true;
    }

    public function urlExists($url) {

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($httpCode >= 200 && $httpCode <= 400) {
            return true;
        } else {
            return false;
        }

        curl_close($handle);
    }

    public static function getActions($categoryId = 0) {
        $user = JFactory::getUser();
        $result = new JObject;

        if (empty($categoryId)) {
            $assetName = 'com_ucoscan';
            $level = 'component';
        } else {
            $assetName = 'com_ucoscan.category.' . (int) $categoryId;
            $level = 'category';
        }

        $actions = JAccess::getActions('com_ucoscan', $level);

        foreach ($actions as $action) {
            $result->set($action->name, $user->authorise($action->name, $assetName));
        }

        return $result;
    }

    public static function addSubmenu($vName = 'ucoscansites') {

        JHtmlSidebar::addEntry(
                JText::_('COM_UCOSCAN_SUBMENU_SUFFIXES'), 'index.php?option=com_ucoscan&view=suffixes', $vName == 'suffixes'
        );

        JHtmlSidebar::addEntry(
                JText::_('COM_UCOSCAN_SUBMENU_DOMAINS'), 'index.php?option=com_ucoscan&view=domains', $vName == 'domains'
        );



        JHtmlSidebar::addEntry(
                JText::_('COM_UCOSCAN_SUBMENU_SITES'), 'index.php?option=com_ucoscan&view=ucoscansites', $vName == 'ucoscansites'
        );

        JHtmlSidebar::addEntry(
                JText::_('COM_UCOSCAN_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&extension=com_ucoscan', $vName == 'categories'
        );
    }

}
