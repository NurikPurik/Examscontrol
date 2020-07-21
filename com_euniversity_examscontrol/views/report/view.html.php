<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('euniversity.php.access.access');


class eUniversityExamsControlViewReport extends JView
{
    public function __construct()
    {
        $this->lib_access = new eUniversity_access();
        $this->_db = &JFactory::getDBO();
        $this->lib_access->AccessCheck('report', 'r', true);
        parent::__construct();
    }

    public function display()
    {
        $model = $this->getModel();
        $this->assignRef('reports', $model->getReports());
        $this->assignRef('model', $model);
        parent::display();
    }
}
