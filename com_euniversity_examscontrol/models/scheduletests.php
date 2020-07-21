<?php
defined('_JEXEC') OR die('Restricted access');

class eUniversityExamsControlModelScheduleTests extends JModel
{
    public function getlistExams()
    {
        if ($login = JRequest::getVar('login')) {
            $db = & JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->from('#__euniversity_exams_schedule as e');
            $query->innerjoin('#__euniversity_users_students_profile as p on p.uid = e.uid AND p.code=' . $db->quote($login));
            $query->leftjoin('#__euniversity_ref_discipline as r_dis on r_dis.ref = e.discipline');
            $query->leftjoin('#__euniversity_cat_discipline as c_dis on c_dis.id = e.discipline');
            $query->select('p.description as student');
            $query->select('e.semestr');
            $query->select('(CASE WHEN c_dis.description IS NULL THEN (CASE WHEN r_dis.description IS NULL THEN e.id ELSE r_dis.description END) ELSE c_dis.description END) as discipline_name');
//            $query->select('r_dis.description as discipline_name');
            $query->select('e.start');
            $query->select('e.sdate');
            $query->select('e.edate');
            $query->select('e.id');
//            $query->select('(case when e.type=1 then "РК1" when e.type=2 then "РК2" when e.type=3 then "Экзамен" end) as type');
            $query->select('e.type');
            $query->select('(e.correct*100/30+e.correct_plus) as correct');
            $query->select('e.calculated');
            $query->select('e.status');
            $query->select('ISNULL(e.result) as result');
//            $query->select('(case when e.sdate IS NULL AND e.calculated=0 AND e.edate IS NULL     AND e.status=1 AND e.result IS NULL     then "<span class=\"icon32 status-green32\" title=\"Неактивный\">-</span>"
//                              when e.sdate IS NOT NULL AND e.calculated=0 AND e.edate IS NULL     AND e.status=2 AND e.result IS NOT NULL then "<span class=\"icon32 pending32 active\" title=\"В процессе\">!</span>"
//                              when e.sdate IS NOT NULL AND e.calculated=1 AND e.edate IS NOT NULL AND e.status=3 AND e.result IS NOT NULL then "<span class=\"icon32 status-green32 active\" title=\"Пройденный\">+</span>"
//                              else "<span class=\"icon32 pending32\" title=\"Неизвестный стутус\">?</span>" end) as status');
            $query->where('e.variant=1');
            $query->order('semestr');
            $query->order('e.type');
            $query->order('discipline_name');
            $db->setQuery($query);
            $list = $db->loadObjectList();
            if ((!$db->getErrorNum())) {
                return $list;
            }
            JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        }
        return null;
    }

    public function getListTests()
    {
        $db = & JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__euniversity_exams_schedule as e');
        $query->leftjoin('#__euniversity_users_students_profile as p on p.uid = e.uid');
        $query->leftjoin('#__euniversity_ref_discipline as r_dis on r_dis.ref = e.discipline');
        $query->leftjoin('#__euniversity_cat_discipline as c_dis on c_dis.id = e.discipline');
        $query->select('p.description as student');
        $query->select('e.result');
        $query->select('e.semestr');
        $query->select('(case when e.type=1 then "РК1" when e.type=2 then "РК2" when e.type=3 then "Экзамен" end) as type');
        $query->select('(CASE WHEN c_dis.description IS NULL THEN (CASE WHEN r_dis.description IS NULL THEN e.id ELSE r_dis.description END) ELSE c_dis.description END) as discipline_name');
        $query->select('(case when e.sdate IS NULL     AND e.calculated=0 AND e.edate IS NULL     AND e.status=1 AND e.result IS NULL     then "Неактивный"
                              when e.sdate IS NOT NULL AND e.calculated=0 AND e.edate IS NULL     AND e.status=2 AND e.result IS NOT NULL then "В процессе"
                              when e.sdate IS NOT NULL AND e.calculated=1 AND e.edate IS NOT NULL AND e.status=3 AND e.result IS NOT NULL then "Пройденный"
                              else "e.status" end) as status');
        $query->select('e.start');
        $query->select('e.sdate');
        $query->select('e.edate');
        $query->select('(case when e.calculated=1 then "Да" else "Нет" end) as calculated');
        $query->select('e.correct');
        $query->select('e.correct_plus');
        $query->select('(e.correct*100/30+e.correct_plus) as mark');
        $query->select('e.id');
        $query->select('e.uid');
        $query->select('p.code');
        $query->where('e.id=' . JRequest::getString('eid'));
        $db->setQuery($query);
        $result = $db->loadObject();
        if ((!$db->getErrorNum())) {
            return $result;
        } else {
            JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
        }

    }
}

