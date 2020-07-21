<?php
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

class eUniversityExamsControlControllerManage extends eUniversityExamsControlController
{
    public function __construct()
    {
        $this->lib_access = new eUniversity_access();

        parent::__construct();
    }

    public function hasCame(){
        $model = $this->getModel('CreateTickets', 'eUniversityExamsControlModel');
        $model->updateTestStatus();
    }
    public function notCame(){
        $model = $this->getModel('CreateTickets', 'eUniversityExamsControlModel');
        $model->updateTestStatus(false);
    }
}

