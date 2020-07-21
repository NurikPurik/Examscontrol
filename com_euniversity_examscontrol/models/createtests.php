<?php
defined('_JEXEC') OR die('Restricted access');

class eUniversityExamsControlModelCreateTests extends JModel
{
    private $msg = array();
    private $type_description = array(1 => 'rk1', 2 => 'rk2', 3 => 'exam');

    public function __construct()
    {
        $this->type = JFactory::getApplication()->getUserState('com_euniversity_examscontrol.createtests.type', 1);
        $this->half = JFactory::getApplication()->getUserState('com_euniversity_examscontrol.createtests.half', 1);
        $this->date = JFactory::getApplication()->getUserState('com_euniversity_examscontrol.createtests.date', JFactory::getDate()->toFormat('%Y-%m-%d'));
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
        $query->select('CONCAT("[", dis.code, "] ", dis.description) as discipline_name');
        $query->select('l.description as lang_name');
        $query->group('d.lang');
        $query->group('d.course');
        $query->group('d.discipline');
        $query->where('d.active=1');
        $query->where('d.control_' . $this->type_description[$this->type] . '=1 AND d.control_' . $this->type_description[$this->type] . '_date IS NOT NULL AND d.control_' . $this->type_description[$this->type] . '_type = "КТ"');
        $query->where('d.apply=1');
        
        $query->where('d.control_' . $this->type_description[$this->type] . '_date=' . $db->quote($this->date));
        
        $query->where('mod(d.semestr,2)=' . $this->half);
        $query->order('discipline_name');
        $query->order('d.semestr');
        $query->order('lang_name');
        $db->setQuery($query);
        $list = $db->loadObjectList();
        if ((!$db->getErrorNum())) {
            return $list;
        }
        JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        return false;
    }

    public function getListTeachers()
    {
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__euniversity_cat_teachers_rup as d');
        $query->leftjoin('#__euniversity_users_teachers_profile as p on p.ref = d.teacher');
        $query->select('p.description');
        $query->select('p.uid as `key`');
        $query->select('d.teacher as ref');

        $query->where('d.active=1');
        $query->where('d.control_' . $this->type_description[$this->type] . '=1 AND d.control_' . $this->type_description[$this->type] . '_date IS NOT NULL AND d.control_' . $this->type_description[$this->type] . '_type = "КТ"');
        $query->where('d.apply=1');
        $query->where('p.uid IS NOT NULL');
        $query->group('d.teacher');
        if ($discipline = JRequest::getString('discipline', false)) {
            $query->where('d.discipline=' . $db->quote($discipline));
        }
        if ($semestr = JRequest::getString('semestr', false)) {
            $query->where('d.semestr=' . $db->quote($semestr));
        }
        $query->where('mod(d.semestr,2)=' . $this->half);
        if ($lang = JRequest::getString('lang', false)) {
            $query->where('d.lang=' . $db->quote($lang));
        }

        $query->where('d.control_' . $this->type_description[$this->type] . '_date=' . $db->quote($this->date));

        $query->order('description');
        $db->setQuery($query);
        $list = $db->loadObjectList('key');
        if ((!$db->getErrorNum())) {
            return $list;
        }
        JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        return false;
    }

    public function getListStudents()
    {
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__euniversity_cat_teachers_rup_students as ds');
        $query->leftjoin('#__euniversity_cat_teachers_rup as d on d.ref = ds.doc_ref');
        $query->leftjoin('#__euniversity_users_students_profile as p on p.ref = ds.student');
        $query->leftjoin('#__euniversity_ref_discipline as dis on dis.ref = d.discipline');
        $query->leftjoin('#__euniversity_cat_edu_lang as l on l.ref = d.lang');
        $query->leftjoin('#__euniversity_cat_edu_specs as s on s.ref = d.spec');
        $query->select('ds.student as ref');
        $query->select('p.description');
        $query->select('p.uid as `key`');
        $query->select('dis.description as discipline_name');
        $query->select('d.semestr');
        $query->select('d.teacher as teacher_ref');
        $query->select('l.description as lang_name');
        $query->select('s.description as spec_name');
        $query->select('s.code as spec_code');
        
        $query->select('d.control_' . $this->type_description[$this->type] . '_date as date');
        
        $query->select('d.stime as stime');
        $query->where('d.active=1');
        $query->where('d.control_' . $this->type_description[$this->type] . '=1 AND d.control_' . $this->type_description[$this->type] . '_date IS NOT NULL AND d.control_' . $this->type_description[$this->type] . '_type = "КТ"');
        $query->where('d.apply=1');
        $query->where('p.uid IS NOT NULL');
        $query->group('ds.student');
        if ($discipline = JRequest::getString('discipline', false)) {
            $query->where('d.discipline=' . $db->quote($discipline));
        }
        if ($semestr = JRequest::getString('semestr', false)) {
            $query->where('d.semestr=' . $db->quote($semestr));
        }
        $query->where('mod(d.semestr,2)=' . $this->half);
        if ($lang = JRequest::getString('lang', false)) {
            $query->where('d.lang=' . $db->quote($lang));
        }
        
        $query->where('d.control_' . $this->type_description[$this->type] . '_date=' . $db->quote($this->date));
        
        
        $query->order('date');
        
        $query->order('stime');
        $query->order('spec_code');
        $query->order('spec_name');
        $query->order('lang_name');
        $query->order('semestr');
        $query->order('description');
        $db->setQuery($query);
        $list = $db->loadObjectList('key');
        if ((!$db->getErrorNum())) {
            return $list;
        }
        JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        return false;
    }

    public function getListExams(&$students)
    {
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__euniversity_exams_schedule as e');
        $query->leftjoin('#__euniversity_users_students_profile as p on p.uid = e.uid');
        $query->select('e.id');
        $query->select('e.tid');
        $query->select('e.uid');
        $query->select('(case when e.sdate IS NULL     AND e.calculated=0 AND e.edate IS NULL     AND e.status=1 AND e.result IS NULL     then "-"
                              when e.sdate IS NOT NULL AND e.calculated=0 AND e.edate IS NULL     AND e.status=2 AND e.result IS NOT NULL then "!"
                              when e.sdate IS NOT NULL AND e.calculated=1 AND e.edate IS NOT NULL AND e.status=3 AND e.result IS NOT NULL then "+"
                              else "?" end) as status');
        $keys = array_keys($students);
        $query->where('e.uid  IN (' . implode(',', $keys) . ')');
        if ($discipline = JRequest::getString('discipline', false)) {
            $query->where('e.discipline=' . $db->quote($discipline));
        } else {
            JError::raiseError('10030', 'Отсутствует  обязятельный параметр');
        }
        if ($semestr = JRequest::getString('semestr', false)) {
            $query->where('e.semestr=' . $db->quote($semestr));
        }
        if ($lang = JRequest::getString('lang', false)) {
            $query->where('p.lang=' . $db->quote($lang));
        }
        $query->where('e.type=' . $this->type);

        $db->setQuery($query);

        $list = $db->loadObjectList();
        if ((!$db->getErrorNum())) {
            foreach ($list as $e) {
                $students[$e->uid]->tid .= '<p>' . $e->id . ':' . $e->tid . ':' . $e->status . '</p>';
            }
        } else {
            JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        }
    }
}
