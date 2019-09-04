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

class UcoscanControllerDomain extends JControllerForm {

    public function save($key = null, $urlVar = null) {

        require_once JPATH_COMPONENT . '/helpers/ucoscan.php';
        // Check for request forgeries.
        //	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        $lang = JFactory::getLanguage();
        $model = $this->getModel('domain');
        $table = $model->getTable();
        $data = $this->input->post->get('jform', array(), 'array');
        $checkin = property_exists($table, 'checked_out');
        $context = "$this->option.edit.$this->context";
        $task = $this->getTask();

        $domain = $data['title'];
        $array_domain = explode(" ", $domain);

        $legth_of_domain = sizeof($array_domain);


        $msg_legth_of_domain = 'You are allowed to enter MAX 3 characters/substrings for a domain, separated by 2 spaces';


        $app->setUserState($context . '.data', $data);
        $url = 'index.php?option=com_ucoscan&view=domain&layout=edit';



        if (($legth_of_domain < 4)) {
            parent::save($data, $urlVar);
        } else

        if ($legth_of_domain > 3) {
            $this->setRedirect($url, $msg_legth_of_domain);
            return false;
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
