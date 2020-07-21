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

    public function createtickets(){
        JRequest::checkToken() or $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtickets&date='.JRequest::getString('date'),'Можно запускать только один интерфейс создания, закройте все страницывсе страницы с созданием и попробуйте ещё раз','Notice');
        JUtility::getToken(true);
        $model = $this->getModel('CreateTickets', 'eUniversityExamsControlModel');
        $streams = $model->save();
		/*echo 'ascascasca';
		echo '<pre>';
		print_r($streams);
		echo '</pre>';
		exit;*/
        if(!empty($streams)){
            $uid = uniqid();
            $streams->uid = $uid;
            foreach($streams->streams as &$stream){
                $stream = json_decode(base64_decode($stream));
            }
//            unlink($stream);
            unset($stream);
//            echo $uid;
           JFactory::getApplication()->setUserState('com_euniversity_examscontrol.manage.token'.$uid, $streams);
//          $t2 = JFactory::getApplication()->getUserState('com_euniversity_examscontrol.manage.token'.$uid,null);
//echo "<pre>";
//           var_dump($t2);

            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtickets&layout=listtickets&date='
            . JRequest::getString('date')
            . '&uid=' . $uid
            , 'Шифрованое тестирование назначено','info');

//            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtickets&date='.JRequest::getString('date'),'Шифрованое тестирование назначено');
        }else{
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtickets&date='.JRequest::getString('date'),'Произошла ошибка попробуйте позднее','error');
        }
    }

    public function addontickets(){
        JRequest::checkToken() or $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtickets&date='.JRequest::getString('date'),'Можно запускать только один интерфейс создания, закройте все страницывсе страницы с созданием и попробуйте ещё раз','Notice');
        JUtility::getToken(true);

        $students = JRequest::getVar('students');
        $test_id = JRequest::getInt('test_id');
        $date = JRequest::getString('date');

        if(empty($students) or empty($test_id) or empty($date)){
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtickets&date='.JRequest::getString('date'),'Ошибка передачи параметра','Warning');
        }
        $db         = &JFactory::getDbo();
        $query      = $db->getQuery(true);
        $query->insert('#__euniversity_exams_list');
        $query->columns('ticket');
        $query->columns('student');
        $query->columns('begin');
        $query->columns('cdate');
        $students = json_decode(base64_decode($students));
        foreach($students as $student){
                $query->values(
                    $db->quote($test_id) . ',' .
                    $db->quote($student) . ',' .
                    $db->quote($date) . ',' .
                    'NOW()'
                );
        }
        $db->setQuery($query);
        $db->query();
        if(!$db->getErrorNum()){
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtickets&date='
                . JRequest::getString('date')
                , 'Шифрованое тестирование доназначено','info');

        }else{
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtickets&date='.JRequest::getString('date'),'Произошла ошибка попробуйте позднее','error');
        }
    }

    public function create()
    {
        JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));
        $app = &JFactory::getApplication();
        $students = JRequest::getVar('students', array(), 'post', 'array');
        $msg = array();
        $type = JRequest::getInt('type');
        $new_date = JRequest::getBool('new_date');
        if (!empty($type)) {
            $db = & JFactory::getDbo();
            $select = $db->getQuery(true);

            $select->from('#__euniversity_exams_schedule');
            $select->select('id');
            $select->select('uid');
            $select->where('discipline=' . $db->quote(JRequest::getString('discipline')));
            $select->where('year=' . $db->quote($app->getCfg('study_year')));
            $select->where('tid=' . $db->quote(JRequest::getInt('questionarie', 0)));
            $select->where('type=' . $db->quote(JRequest::getString('type')));
            $select->where('old_exams=0');
            $select->where('variant=1');
            $keys = array_keys($students);
            $select->where('uid IN (' . implode(',', $keys) . ')');
            $db->setQuery($select);
            $list_u = $db->loadObjectList('uid');
            if ($db->getErrorNum()) {
                $msg[] = $db->getErrorNum() . ': ' . $db->getErrorMsg();
            }

            $insert = $db->getQuery(true);
            $insert->insert('#__euniversity_exams_schedule');
            $insert->columns('cdate');
            $insert->columns('uid');
            $insert->columns('start');
            $insert->columns('discipline');
            $insert->columns('semestr');
            $insert->columns('year');
            $insert->columns('tid');
            $insert->columns('type');
            $insert->columns('status');

            foreach ($students as $key => $student) {
                $student = explode(' ', $student);
                if (empty($list_u[$key])) {
                    $value = 'NOW()';
                    $value .= ',' . $key;
                    if ($new_date) {
                        $value .= ',' . $db->quote(JRequest::getString('new_date_value', JFactory::getDate()->toFormat('%Y-%m-%d')));
                    } else {
                        $value .= ',' . $db->quote($student[0] . ' ' . $student[1]);
                    }
                    $value .= ',' . $db->quote(JRequest::getString('discipline'));
                    $value .= ',' . $db->quote($student[2]);
                    $value .= ',' . $db->quote($app->getCfg('study_year'));
                    $value .= ',' . $db->quote(JRequest::getInt('questionarie', 0));
                    $value .= ',' . $db->quote(JRequest::getInt('type'));
                    $value .= ',1';
                    $insert->values($value);
                } else {
                    $update = $db->getQuery(true);
                    $update->update('#__euniversity_exams_schedule');
                    $update->set('start=' . $db->quote($student[0] . ' ' . $student[1]));
                    $update->where('id=' . $list_u[$key]->id);
                    $db->setQuery($update);
                    $db->query();
                    if ($db->getErrorNum()) {
                        $msg[] = $db->getErrorNum() . ': ' . $db->getErrorMsg();
                    }
                }
            }
            if (!empty($value)) {
                $db->setQuery($insert);
                $db->query();
                if ($db->getErrorNum()) {
                    $msg[] = $db->getErrorNum() . ': ' . $db->getErrorMsg();
                }
            }
            JFactory::getApplication()->setUserState('com_euniversity_examscontrol.createtests.type', JRequest::getInt('type', 1));
            JFactory::getApplication()->setUserState('com_euniversity_examscontrol.createtests.half', JRequest::getInt('half', 1));
            JFactory::getApplication()->setUserState('com_euniversity_examscontrol.createtests.date', JRequest::getString('date', JFactory::getDate()->toFormat('%Y-%m-%d')));
        } else {
            $msg[] = 'Внутренняя ошибка';
        }
        if (empty($msg)) {
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtests', 'Расписание добавлено');
        } else {
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=createtests', 'Ошибка добавления расписание<p>' . implode(';<br />', $msg) . '</p>', 'error');
        }
    }

    public function hasCame(){
        echo "OK";
    }

    public function clear()
    {
        JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));
        $eid = JRequest::getInt('eid');
        if (!empty($eid)) {
            $db = & JFactory::getDbo();
            $update = $db->getQuery(true);
            $update->update('#__euniversity_exams_schedule');
            $update->set('correct=0');
            $update->set('sdate=NULL');
            $update->set('edate=NULL');
            $update->set('calculated=0');
            $update->set('etype=0');
            $update->set('status=1');
            $update->set('start=NOW()');
            $update->set('result=NULL');
            $update->set('old_exams=NULL');
            $update->where('id=' . $eid);
            $db->setQuery($update);
            $db->query();
            if ($db->getErrorNum()) {
                $msg[] = $db->getErrorNum() . ': ' . $db->getErrorMsg();
            }
        } else {
            $msg[] = 'Пустое значение EID';
        }

        if (empty($msg)) {
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=scheduletests&login=' . JRequest::getVar('ologin'), 'Контроль обновлен');
        } else {
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=scheduletests', 'Ошибка отчистки контроля<p>' . implode(';<br />', $msg) . '</p>', 'error');
        }
    }

    public function recalculate()
    {
        JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));
        $eid = JRequest::getInt('eid');
        $result = 0;
        if (!empty($eid)) {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->from('#__euniversity_exams_schedule');
            $query->select('result');
            $query->where('id=' . $eid);
            $db->setQuery($query);
            if ($item = $db->loadResult()) {
                foreach (unserialize($item) as $row) {
                    if ($row['c'] == $row['a']) {
                        $result++;
                    }
                }
                $query = $db->getQuery(true);
                $query->update('#__euniversity_exams_schedule');
                $query->where('id=' . $eid);
                $query->set('calculated=1');
                $query->set('correct=' . $result);
                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum()) {
                    $msg[] = $db->getErrorNum() . ': ' . $db->getErrorMsg();
                }
            } else {
                $msg[] = 'Пустой тест';
            }
        } else {
            $msg[] = 'Пустое значение EID';
        }

        if (empty($msg)) {
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=scheduletests&login=' . JRequest::getVar('ologin'), 'Результат пересчитан');
        } else {
            $this->setRedirect('index.php?option=com_euniversity_examscontrol&view=scheduletests', 'Ошибка пересчета результата<p>' . implode(';<br />', $msg) . '</p>', 'error');
        }
    }
}

