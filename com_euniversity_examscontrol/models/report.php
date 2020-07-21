<?php
defined('_JEXEC') OR die('Restricted access');

class eUniversityExamsControlModelReport extends JModel
{
    public function getReports()
    {
        return JFolder::files('components' . DS . 'com_euniversity_examscontrol' . DS . 'views' . DS . 'report' . DS . 'tmpl', 'default_.');
    }


}

