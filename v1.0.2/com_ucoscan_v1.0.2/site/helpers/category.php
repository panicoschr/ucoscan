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

class UcoscanCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__ucoscan_ucoscansite';
		$options['extension'] = 'com_ucoscan';
		parent::__construct($options);
	}
}