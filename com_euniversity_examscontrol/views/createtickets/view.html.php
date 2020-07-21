<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');

class eUniversityExamsControlViewCreateTickets extends JView
{
    public function __construct()
    {
        $this->lib_access = new eUniversity_access();
	$this->lib_access->setComponent('com_euniversity_exams');
        $this->lib_access->AccessCheck('opentest_gen', 'r', true);
        parent::__construct();
    }

    public function display()
    {
        $model = $this->getModel();
        $tpl = JRequest::getVar('layout');
        switch ($tpl) {
            case 'liststreams';
                JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));

                $this->assignRef('discipline', $model->getCat('ref_discipline','discipline'));
                $this->assignRef('lang', $model->getCat('cat_edu_lang','lang'));
                $this->assignRef('listStreams', $model->getListStreams());
                $this->assignRef('listTests', $model->getListTests());
                break;
            case 'listtickets';
                $uid = JRequest::getString('uid');

                $this->streams = new stdClass;
                if (!empty($uid)){
                    $this->streams = JFactory::getApplication()->getUserState('com_euniversity_examscontrol.manage.token'.$uid,null);
                    if(empty($this->streams))$this->lib_access->youDie(10000);
                    $this->streams->count = 0;
                    foreach($this->streams->streams as &$stream){
                        $this->streams->count += $stream->students_count;
                    }
                    unset($stream);
                    JRequest::setVar('discipline',$this->streams->discipline->ref);
                    JRequest::setVar('semestr',$this->streams->semestr);
                    JRequest::setVar('lang',$this->streams->lang->ref);
                } else {
                    $this->streams->test_id     = JRequest::getInt('test_id');
                    $this->streams->year        = JRequest::getInt('year',JFactory::getApplication()->getCfg('euniversity_current_year'));
                    $this->streams->semestr     = JRequest::getInt('semestr');
                    $this->streams->date        = JRequest::getString('date', JFactory::getDate()->toFormat('%Y-%m-%d'));
                    $this->streams->discipline  = $model->getCat('ref_discipline','discipline');
                    $this->streams->lang        = $model->getCat('cat_edu_lang','lang');
                    $this->streams->count       = JRequest::getInt('students_count');
                    $this->streams->streams[JRequest::getString('stream_key')]->students_count = JRequest::getInt('students_count');
                    $this->streams->streams[JRequest::getString('stream_key')]->spec_name = JRequest::getString('spec_name');
                    $this->streams->streams[JRequest::getString('stream_key')]->teacher_name = JRequest::getString('teacher_name');
                    $this->streams->uid = uniqid();
                }

                $this->streams->test_data              = $model->getListTests($this->streams->test_id);
//                $this->streams->tickets           = $model->generateTickets($this->streams->test_id, $this->streams->count);
                $test = current($this->streams->test_data);
                if(!empty($test->test_data)){
                    $this->streams->test_data = json_decode($test->test_data);
                }

                $this->streams->protocol_id          = !empty($this->streams->test_data->protocol_id) ? $this->streams->test_data->protocol_id : null;
                $this->streams->protocol_date        = !empty($this->streams->test_data->protocol_date) ? $this->streams->test_data->protocol_date : JFactory::getDate()->toFormat('%Y-%m-%d');

                JFactory::getApplication()->setUserState('com_euniversity_examscontrol.manage.token'.$this->streams->uid,$this->streams);
                break;
            default:
                $tpl = null;
                $this->assignRef('listDocs', $model->getListDocs());
                break;
        }
        parent::display($tpl);
    }
}
