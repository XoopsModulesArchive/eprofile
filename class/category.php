<?php
/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: category.php 2 2012-08-16 08:20:47Z alfred $
 */
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class EprofileCategory extends XoopsObject
{
    function __construct() 
    {
        parent::__construct();
        $this->initVar('cat_id', 			XOBJ_DTYPE_INT, null, true);
        $this->initVar('cat_title', 		XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_description', 	XOBJ_DTYPE_TXTAREA);
        $this->initVar('cat_weight', 		XOBJ_DTYPE_INT);
    }   
    
    
    /**
    * Get {@link XoopsThemeForm} for adding/editing categories
    *
    * @param mixed $action URL to submit to or false for $_SERVER['REQUEST_URI']
    *
    * @return object
    */
    function getForm($action = false)
    {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? sprintf(_EPROFILE_AM_ADD, _EPROFILE_AM_CATEGORY) : sprintf(_EPROFILE_AM_EDIT, _EPROFILE_AM_CATEGORY);

        include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->addElement(new XoopsFormText(_EPROFILE_AM_TITLE, 'cat_title', 35, 255, $this->getVar('cat_title')));
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('id', $this->getVar('cat_id')));
        }
        $form->addElement(new XoopsFormTextArea(_EPROFILE_AM_DESCRIPTION, 'cat_description', $this->getVar('cat_description', 'e')));
        $form->addElement(new XoopsFormText(_EPROFILE_AM_WEIGHT, 'cat_weight', 35, 35, $this->getVar('cat_weight', 'e')));

        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
	
}

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class EprofileCategoryHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        parent::__construct($db, "profile_category", "Eprofilecategory", "cat_id", 'cat_title');
    }

	function getNewId()
	{
		$sql = "SELECT cat_id FROM " . $this->db->prefix("profile_category") . " ORDER BY cat_id DESC LIMIT 1";
		$result = $this->db->query($sql);
		list($r_id) = $this->db->fetchRow($result);
		$r_id++;
		return $r_id;
	}

}
?>