<?php
defined('_JEXEC') OR die('Restricted access');

class eUniversityExamsControlModelCreateTickets extends JModel
{
    public function __construct()
    {
        $this->lib_access = new eUniversity_access();
        $this->lib_access->setComponent('com_euniversity_exams');
        $this->lib_access->AccessCheck('opentest_gen', 'r', true);
        $this->cyear = (int)JFactory::getApplication()->getCfg('euniversity_current_year');

        $this->cyear = '1920';
        //echo $this->cyear;
        parent::__construct();
    }

    public function getListDocs()
    {
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__euniversity_cat_teachers_rup as d');
        $query->leftjoin('#__euniversity_ref_discipline as dis on dis.ref = d.discipline');
        $query->leftjoin('#__euniversity_cat_edu_lang as l on l.ref = d.lang');
        $query->leftjoin('#__euniversity_cat_edu_specs as s on s.ref = d.spec');
        $query->select('d.*');
        $query->select('dis.description as discipline_name');
        $query->select('l.description as lang_name');
        $query->select('s.description as spec_name');
        $query->select('s.code as spec_code');
        $query->group('lang');
        $query->group('course');
        $query->group('discipline');
        $query->where('d.active=1');
        $query->where('d.control_exam=1 AND d.control_exam_date IS NOT NULL AND d.control_exam_type = "ОТ"');
        $query->where('d.apply=1');
        $query->where('d.year=' . $db->quote($this->cyear));
        $query->where('d.control_exam_date=' . $db->quote(JRequest::getString('date', JFactory::getDate()->toFormat('%Y-%m-%d'))));
	$query->where('mod(d.semestr,2)=' . $db->quote(JRequest::getString('half', 0)));
        $query->order('discipline_name');
        $query->order('semestr');
        $query->order('lang_name');
        $db->setQuery($query);
        $list = $db->loadObjectList();
        if(!$db->getErrorNum()) {
            return $list;
        }
        JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        return false;
    }

    public function getListStreams()
    {
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__euniversity_cat_teachers_rup_students as rts');
        $query->leftjoin('#__euniversity_cat_teachers_rup as rt on rt.ref = rts.doc_ref');
        $query->leftjoin('#__euniversity_cat_edu_specs as cs on cs.ref = rt.spec');
        $query->leftjoin('#__euniversity_users_teachers_profile as pt on pt.ref = rt.teacher');
        $query->leftjoin('#__euniversity_users_students_profile as ps on ps.ref = rts.student');
        $query->select('rt.semestr as semestr');
        $query->select('rt.year as year');
        $query->select('rt.teacher as teacher');
        $query->select('rts.student as student');
        $query->select('cs.ref as spec_key');
        $query->select('cs.description as spec_name');
        $query->select('cs.code as spec_code');
        $query->select('ps.description as student_name');
        $query->select('ps.code as student_code');
        $query->select('pt.description as teacher_name');
        $query->where('rt.active=1');
        $query->where('rt.control_exam="1"');
        $query->where('rt.control_exam_date IS NOT NULL');
        $query->where('rt.control_exam_type = "ОТ"');
        $query->where('rt.year=' . $db->quote($this->cyear));
        $query->where('rt.apply="1"');
        $query->where('rt.discipline=' . $db->quote(JRequest::getString('discipline', false)));
        $query->where('rt.semestr=' . $db->quote(JRequest::getInt('semestr', false)));
        $query->where('rt.lang=' . $db->quote(JRequest::getString('lang', false)));
        $query->where('rt.control_exam_date=' . $db->quote(JRequest::getString('date', false)));
        $query->order('spec_code');
        $query->order('spec_name');
        $query->order('semestr');
        $query->order('teacher_name');
        $query->order('student_name');
        $db->setQuery($query);
        $list = $db->loadObjectList('student');
        if(!$db->getErrorNum()) {
            return $list;
        }
        JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        return false;
    }

    public function getListTests($id = null)
    {
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__euniversity_exams_tickets as t');
        $query->leftjoin('#__euniversity_exams_list as l on l.ticket = t.id');
        $query->leftjoin('#__euniversity_users_students_profile as p on p.ref = l.student');
        $query->leftjoin('#__euniversity_cat_edu_specs as cs on cs.ref = p.specs');
        $query->select('t.id as test_id');
        $query->select('t.data as test_data');
        $query->select('p.specs as spec_key');
        $query->select('cs.code as spec_code');
        $query->select('cs.description as spec_name');
        $query->select('l.student as student');
        $query->select('l.id as clear_code');
        $query->select('l.status as status');
        $query->where('t.year=' . $db->quote($this->cyear));
        $query->where('t.type=2');
        $query->where('t.discipline=' . $db->quote(JRequest::getString('discipline',false)));
        $query->where('t.semestr=' . $db->quote(JRequest::getInt('semestr', false)));
        $query->where('t.lang=' . $db->quote(JRequest::getString('lang', false)));
        if(!empty($id)){
            $query->where('t.id=' . $db->quote($id));
        }
        $db->setQuery($query);
        $list = $db->loadObjectList('student');
        if(!$db->getErrorNum()) {
            return $list;
        }
        JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        return false;
    }

    public function save(){
        $test = JRequest::getInt('qradio');
		
        if(empty($test)){
			
            $tickets    = $this->createTicket();
            $questions = $this->createQuestions();

            if(!$tickets || !$questions){
                return false;
            }
            if(!$this->query($questions[0])){
                return false;
            }
            if(!($test = $this->query($tickets,true))){
                $this->query('DELETE FROM #__euniversity_exams_newquestions WHERE uuid in (' . implode(',',$questions[1]) . ')');
                return false;
            }
            if(!$this->query(str_replace('$@$',$test,str_replace('INSERT','REPLACE',$questions[0])))){
                $this->query('DELETE FROM #__euniversity_exams_newquestions WHERE uuid in (' . implode(',',$questions[1]) . ')');
                $this->query('DELETE FROM #__euniversity_exams_tickets WHERE id=' . $test);
                return false;
            }
        }
	
        $return              = new stdClass;
//        $return->streams     = JRequest::getVar('streams', array(), 'post');
//        $return->test_id     = 2013;
        $return->test_id     = $test;
        $return->discipline  = $this->getCat('ref_discipline','discipline');
        $return->lang        = $this->getCat('cat_edu_lang','lang');
        $return->year        = JRequest::getInt('year');
        $return->semestr     = JRequest::getInt('semestr');
        $return->date        = JRequest::getString('date', JFactory::getDate()->toFormat('%Y-%m-%d'));

        if($list = $this->createSchedule($test)){
            if($this->query($list)){
                $return->streams = JRequest::getVar('streams', array(), 'post');
                return $return;
				
            }
        }

//        return $return;
        return false;
    }

    private function query($query,$lastid=false){
        $db = & JFactory::getDbo();
        $db->setQuery($query);
//        echo $query;
        $db->query();
        if(!$db->getErrorNum()){
            if($lastid){
                return $db->insertid();
            }
            return true;
        }
        return false;
    }

    private function createSchedule($test){
        $streams    = JRequest::getVar('streams', array(), 'post');
        $db         = &JFactory::getDbo();
        $query      = $db->getQuery(true);
        $query->insert('#__euniversity_exams_list');
        $query->columns('ticket');
        $query->columns('student');
        $query->columns('begin');
        $query->columns('cdate');
        if(!empty($streams)){
            foreach($streams as $stream){
                $stream = json_decode(base64_decode($stream));
                foreach($stream->students_check as $student){
                    $query->values(
                        $db->quote($test) . ',' .
                        $db->quote($student) . ',' .
                        $db->quote(JRequest::getString('date')) . ',' .
                        'NOW()'
                    );
                }
            }
            return $query;
        }
        return false;
    }

    private function createTicket(){
        $year       = JRequest::getString('year');
        $discipline = JRequest::getString('discipline');
        $semestr    = JRequest::getInt('semestr');
        $lang       = JRequest::getString('lang');
        $db         = &JFactory::getDbo();
        $query      = $db->getQuery(true);

        $query->insert('#__euniversity_exams_tickets');

        $query->set('status="1"');
        $query->set('type="2"');

        $query->set('year='       . $db->quote($year));
        $query->set('discipline=' . $db->quote($discipline));
        $query->set('lang='       . $db->quote($lang));
        $query->set('semestr='    . $db->quote($semestr));

        $query->set('controltype="13"');
        $query->set('cdate=NOW()');
        $query->set('creator=' . $db->quote($this->lib_access->getUid()));
		
        return $query;

    }

    private function createQuestions(){
        if(isset($_FILES) && is_array($_FILES)){
            jimport('phpexcel.Classes.PHPExcel');
            jimport('phpexcel.Classes.PHPExcel.IOFactory');
            $db          = &JFactory::getDbo();
            $objPHPExcel = PHPExcel_IOFactory::load($_FILES["questions"]["tmp_name"]);
            $worksheet   = $objPHPExcel->getSheet(0);
			$semestr     = JRequest::getInt('semestr');
			
            $query       = $db->getQuery(true);

            $query->insert('#__euniversity_exams_newquestions');
            $query->columns('uuid');
            $query->columns('tid');
            $query->columns('question');
            $query->columns('level');
            $query->columns('a0');
            $query->columns('a1');
            $query->columns('a2');
            $query->columns('a3');
            $query->columns('a4');
            $uuids = array();
			
			$ar_check = array();
			/*echo '<pre>';
			print_r($list);
			echo '</pre>';
			
			exit;
			if($semestr < 3)
			{
				$page = 3;
				$count = 2;
			}else{
				$page = 4;
				$count = 3;
			}*/
			$count = 3;
			$page = 4;
				
            for ($i = 1; $i <= $count; $i++) {
                $worksheet = $objPHPExcel->getSheet($i);
				
                if($objPHPExcel->getSheetCount() != $page) return false;
                $highestRow = $worksheet->getHighestRow() + 1;
#                if($highestRow < 5) return false;

                for ($row = 1; $row < $highestRow; $row++) {
                    $question = $db->quote(str_replace('\n', '<br />', trim(htmlspecialchars($worksheet->getCellByColumnAndRow(0, $row)->getValue()))));
                  
				   if ($question != "''" and substr($question,0,2) != "'=") {
						$ar_check[$i] = true;
                        $uuids[]=$db->quote($this->uuid());
                        $query->values(end($uuids) . ',"$@$",' . $question . ',' . $db->quote($i) . ',"","","","",""');
						 
                    }
                }
				
            }
			//if($page = 3)
			//{
				//if (isset($ar_check[1]) and isset($ar_check[2]))
				//{
					//return array($query,$uuids);	
				//}
			//}else//{
					if (isset($ar_check[1]) and isset($ar_check[2]) and isset($ar_check[3]))
						{	
							return array($query,$uuids);	
						}
				//}

		
				
			}
        return false;
    }

    public function updateTicket($id){
        $data_changed  = JRequest::getInt('data_changed');
        $uid           = JRequest::getString('uid');
        $streams = JFactory::getApplication()->getUserState('com_euniversity_examscontrol.manage.token'.$uid,null);
		

        if(empty($data_changed)){
            return $streams;
        }

        $protocol_id   = JRequest::getString('protocol_id');
        $protocol_date = JRequest::getString('protocol_date', JFactory::getDate()->toFormat('%Y-%m-%d'));
        $db            = &JFactory::getDbo();
        $query         = $db->getQuery(true);

        $query->update('#__euniversity_exams_tickets');
        $query->set('data=' . $db->quote(json_encode(array('protocol_id'=>$protocol_id,'protocol_date'=>$protocol_date))));
        $query->where('id=' . $db->quote($streams->test_id));
        $db->setQuery($query);
		
        $db->query();
        if(!$db->getErrorNum()) {
            $streams->protocol_id   = $protocol_id;
            $streams->protocol_date = $protocol_date;
            $streams_count  = JRequest::getVar('tickets', array(), 'post');
            $streams->count = 0;
            foreach($streams_count as $key => $count){
//                if($streams->streams[$key]->students_count  != $count){
                    //$students = $count - $streams->streams[$key]->students_count;
                    /*if($students > 0){
                        $tickets  = $this->generateTickets($streams->test_id, $students);
                        if(!$tickets){
                            return false;
                        }
                        $streams->tickets[1] = array_merge($streams->tickets[1],$tickets[1]);
                        $streams->tickets[2] = array_merge($streams->tickets[2],$tickets[2]);
                        $streams->tickets[3] = array_merge($streams->tickets[3],$tickets[3]);
                    }else if($students < 0){
                        for((int)$i=0;$i<abs($students);$i++){
                            array_pop($streams->tickets[1]);
                            array_pop($streams->tickets[2]);
                            array_pop($streams->tickets[3]);
                        }
                    }else{
                        return false;
                    }*/
                    $streams->streams[$key]->students_count  = $count;
                   // $streams->streams[$key]->id  = 'scasc';
                    $streams->count = $streams->count + $count;
//                }
            }
            return $streams;
        }
        return false;
    }

    private function uuid() {
        $db = &JFactory::getDbo();
        $db->setQuery('SELECT UUID();'); 
        return $db->loadResult();
    }

    public function generateTickets($id, $count = null, $semestr, $spec){
        $db    = &JFactory::getDbo();
        $query = array();
        $result = array();
		list($scep_ref,$musor) = explode("--", key($spec));
		//echo $scep_ref;
		//exit;
		
		if($semestr <= 2)
		{
			if(
                $scep_ref == '3e97c199-e343-11e9-80da-025400b39f57'      //Международное право (уск.)
				OR 	$scep_ref == '3e97c197-e343-11e9-80da-025400b39f57'		//Маркетинг и бизнес - коммуникации (уск.)
				OR 	$scep_ref == '3e97c195-e343-11e9-80da-025400b39f57'		//Государственное управление и менеджмент (уск,)
				OR 	$scep_ref == '68de1687-d8ca-11e9-80d7-025400b39f57'		//Международное право (уск.)	
				OR 	$scep_ref == '433a9d19-c525-11e9-80d2-025400b39f57'		//Международные отношения (уск.)
				OR 	$scep_ref == '1bdd15ff-a383-11e9-80ce-025400b39f57'		//Юриспруденция (уск.)	
				OR 	$scep_ref == '1bdd15fd-a383-11e9-80ce-025400b39f57'		//Экономика (уск.)	
				OR 	$scep_ref == '1bdd15fa-a383-11e9-80ce-025400b39f57'		//Учитель двух иностранных языков (уск.)
				OR 	$scep_ref == '4b40ec13-b277-11e9-80d1-025400b39f57'		//Туризм (уск.)
				OR 	$scep_ref == '4b40ec11-b277-11e9-80d1-025400b39f57'		//Ресторанное дело и гостиничный бизнес (уск.)
				OR 	$scep_ref == '4b40ebf0-b277-11e9-80d1-025400b39f57'		//Переводческое дело (китайский язык уск.)
				OR 	$scep_ref == '1bdd1601-a383-11e9-80ce-025400b39f57'		//Иностранная филология уск.
			
				)
			{
				$levels = 3;
			}
            else
			{
				$levels = 2;
			}			
		}
        else
		{
			$levels = 3;
		}
       


        for($i=1;$i<=$levels;$i++){
             $query[$i] = $db->getQuery(true);
             $query[$i]->from('#__euniversity_exams_newquestions as q');
             $query[$i]->select('q.question');
             $query[$i]->where('tid=' . $db->quote($id));
             $query[$i]->where('level=' . $db->quote($i));
             if(!empty($count)){
                 $query[$i]->order('RAND() LIMIT ' . $count);
             }
            
        }
         
        foreach($query as $key => $q){
            $db->setQuery($q);
            
            $result[$key] = $db->loadObjectList();

	    if (count($result[$key])<$count)
	    {
		for ($i=count($result[$key]);$i<$count;$i++)
		{
		    $rand = array_rand($result[$key]);
		    $result[$key][$i] = $result[$key][$rand];
		}
	    }
        }
     /*  echo '<pre>';
        print_r($result);
        echo '</pre>';
         
            exit;*/
        return $result;
    }

    public function getCat($table,$key){
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__euniversity_' . $table);
        $query->select('description, code, ref');
        $query->where('ref=' . $db->quote(JRequest::getString($key)));

        $db->setQuery($query);
        $result = $db->loadObject();
        if(!$db->getErrorNum()) {
            return $result;
        }
        JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        return false;
    }

    public function encodeOpenTest($code)
    {
/*        (integer)666 => (string)0584-9088*/
        if (empty($code) or ($code < 1) or ($code > 16777215)){
            return '';
        }

        $id           = sprintf("%07d", $code);
        $id_bin       = sprintf ("%024b", $id);
        $rev_id_bin   = strrev($id_bin);
        $rev_id       = sprintf("%08d", bindec($rev_id_bin));
        $encrypt_code = sprintf("%04d-%04d", substr($rev_id, 0, 4), substr($rev_id, 4, 4));

        return $encrypt_code;
    }

    public function updateTestStatus($flag=true){
        $exam_id = substr(JRequest::getString('clear_code'),5);
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update('#__euniversity_exams_list');
        $query->set('status=' . (int)$flag);
        $query->where('id=' . $db->quote($exam_id));
        $db->setQuery($query);
        $db->query();
        if(!$db->getErrorNum()) {
            echo json_encode($this->encodeOpenTest($exam_id));
            return true;
        }
        return false;
    }
}