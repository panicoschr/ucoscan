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

class UcoscanControllerUcoscansite extends JControllerForm {

    public function save($key = null, $urlVar = null) {

        require_once JPATH_COMPONENT . '/helpers/ucoscan.php';
        // Check for request forgeries.
        //	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $model = $this->getModel('ucoscansite');
        $table = $model->getTable();
        $data = $this->input->post->get('jform', array(), 'array');
        $checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
        $task = $this->getTask();

        $domainandid = $data['domainandid'];
        $domainpos = strpos($domainandid, "the id is");
        $domain = substr($domainandid, 0, $domainpos);
        $array_domain = explode(" ", $domain);

        $legth_of_domain = sizeof($array_domain);




        $msg_check_No_Of_Chars = 'No of characters must by ranged from 1 to ' . $legth_of_domain;
        $msg_check_cms_selection = 'Please select at least one CMS';
        $msg_check_domain_selection = 'Please select a Domain - There must be at least one published Domain';
        $msg_check_suffix_publishing = 'There must be at least one published Suffix';
        

        $app->setUserState($context . '.data', $data);
        $url = 'index.php?option=com_ucoscan&view=ucoscansite&layout=edit';

        if ($data[id] == '0') {
            $check_No_Of_Chars = $model->check_No_Of_Chars($data);
            $check_cms_selection = $model->check_cms_selection($data);
            $check_domain_selection  = $model->check_domain_selection($data);
            $check_suffix_publishing = $model->check_suffix_publishing();

            if (($check_No_Of_Chars) && ($check_cms_selection) && ($check_domain_selection)
                    && ($check_suffix_publishing)) {
                
                $sites_ok = $model->constructUrls($data);
                if ($sites_ok) {
                    parent::save($data, $urlVar);
                }
            } else
            if ((!$check_No_Of_Chars) || (!$check_cms_selection)|| 
                    (!$check_domain_selection) || (!$check_suffix_publishing) ) {
                if (!$check_No_Of_Chars) {
                    $this->setRedirect($url, $msg_check_No_Of_Chars);
                    return false;
                }
                if (!$check_cms_selection) {
                    $this->setRedirect($url, $msg_check_cms_selection);
                    return false;
                }
                 if (!$check_domain_selection) {
                    $this->setRedirect($url, $msg_check_domain_selection);
                    return false;
                }
                 if (!$check_suffix_publishing) {
                    $this->setRedirect($url, $msg_check_suffix_publishing);
                    return false;
                }
            }
        } else {
            parent::save($data, $urlVar);
        }
    }

    protected function allowAdd($data = array()) {
        $user = JFactory::getUser();
        $categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
        $allow = null;

        if ($categoryId) {
            // If the category has been passed in the URL check it.
            $allow = $user->authorise('core.create', $this->option . '.category.' . $categoryId);
        }

        if ($allow === null) {
            // In the absense of better information, revert to the component permissions.
            return parent::allowAdd($data);
        } else {
            return $allow;
        }
    }

    protected function allowEdit($data = array(), $key = 'id') {
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $categoryId = 0;

        if ($recordId) {
            $categoryId = (int) $this->getModel()->getItem($recordId)->catid;
        }

        if ($categoryId) {
            // The category has been set. Check the category permissions.
            return JFactory::getUser()->authorise('core.edit', $this->option . '.category.' . $categoryId);
        } else {
            // Since there is no asset tracking, revert to the component permissions.
            return parent::allowEdit($data, $key);
        }
    }

}
