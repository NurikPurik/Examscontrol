<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class eUniversityExamsControlViewScheduleTests extends JView
{
    public function __construct()
    {
        $this->lib_access = new eUniversity_access();
        $this->lib_access->AccessCheck('scheduletests_list', 'r', true);
        $types = array('РК1', 'РК2', 'Экзамен');
        $status = array('Неактивный', 'В процессе', 'Пройденный', 'Неизвестный стутус');
        $this->assignRef('types', $types);
        $this->assignRef('status', $status);
        parent::__construct();
    }

    public function display()
    {
        $model = $this->getModel();
        $tpl = JRequest::getVar('layout');
        switch ($tpl) {
            case 'info';
                JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));
                $this->assignRef('listTests', $model->getListTests());
                break;
            default:
                $tpl = null;
                $this->assignRef('listExams', $model->getListExams());
                break;
        }
        parent::display($tpl);
    }

    protected function getStatus(&$exam)
    {
//	var_dump($exam->result);
        if (empty($exam->sdate) and empty($exam->edate) and !empty($exam->result) and empty($exam->calculated) and ($exam->status == 1)) {
            return '<span class="icon32 status-green32 hasTip" title="Неактивный">-</span>';
        } elseif (!empty($exam->sdate) and empty($exam->edate) and empty($exam->result) and empty($exam->calculated) and ($exam->status == 2)) {
            return '<span class="icon32 pending32 active hasTip" title="В процессе">!</span>';
        } elseif (!empty($exam->sdate) and !empty($exam->edate) and empty($exam->result) and !empty($exam->calculated) and ($exam->status == 3)) {
            return '<span class="icon32 status-green32 active" title="Пройденный">+</span>';
        }else{
            return '<span class="icon32 pending32 hasTip" title="' . $exam->status . '">?</span>';
        }


    }
}
