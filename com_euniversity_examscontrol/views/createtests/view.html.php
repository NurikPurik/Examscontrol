<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class eUniversityExamsControlViewCreateTests extends JView
{
    protected $type_description = array(1 => 'Рубежный Контроль №1', 2 => 'Рубежный Контроль №2', 3 => 'Экзамен');
    protected $half_description = array(0 => 'Летняя сессия', 1 => 'Зимняя сессия');

    public function __construct()
    {

        $this->type = JRequest::getInt('type', JFactory::getApplication()->getUserState('com_euniversity_examscontrol.createtests.type', 1));
        JFactory::getApplication()->setUserState('com_euniversity_examscontrol.createtests.type', $this->type);
        $this->half = JRequest::getInt('half', JFactory::getApplication()->getUserState('com_euniversity_examscontrol.createtests.half', 1));
        JFactory::getApplication()->setUserState('com_euniversity_examscontrol.createtests.half', $this->half);
        $this->date = JRequest::getString('date', JFactory::getApplication()->getUserState('com_euniversity_examscontrol.createtests.date', JFactory::getDate()->toFormat('%Y-%m-%d')));
        JFactory::getApplication()->setUserState('com_euniversity_examscontrol.createtests.date', $this->date);

        $this->lib_access = new eUniversity_access();
        $this->lib_access->AccessCheck('createtests_list', 'r', true);
        parent::__construct();
    }

    public function display()
    {
        $model = $this->getModel();
        $tpl = JRequest::getVar('layout');
        switch ($tpl) {
            case 'students';
                JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));
                $this->assignRef('listStudents', $model->getListStudents());
                $this->assignRef('listTeachers', $model->getListTeachers());
                $model->getListExams($this->listStudents);
                break;
            default:
                $tpl = null;
                $this->assignRef('listDocs', $model->getListDocs());
                break;
        }
        parent::display($tpl);
    }
}
