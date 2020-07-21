<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');

class eUniversityExamsControlController extends JController
{
    public function display()
    {
        parent::display(true);
    }

  public function encode_opentest($code)
  {
    # (integer)666 => (string)0584-9088

    if (empty($code) or $code < 1 or $code > 16777215)
    {
      return '';
    }

    $id = sprintf("%07d", $code);
    $id_bin = sprintf("%024b", $id);
    $rev_id_bin = strrev($id_bin);
    $rev_id = sprintf("%08d", bindec($rev_id_bin));
    $encrypt_code = sprintf("%04d-%04d", substr($rev_id, 0, 4), substr($rev_id, 4, 4));

    return $encrypt_code;
  }

  public function decode_opentest($code)
  {
    # (string)0584-9088 => (integer)666

    if (preg_match('/^\d{4}-\d{4}$/', $code) === 0)
    {
      return 0;
    }

    $rev_id = sprintf("%04d%04d", substr($code, 0, 4), substr($code, 5, 4));
    $rev_id_bin2 = sprintf("%024b", $rev_id);
    $id_bin2 = strrev($rev_id_bin2);
    $id2 = sprintf("%07d", bindec($id_bin2));
    $decrypt_code = (integer)$id2;

    return $decrypt_code;
  }

    public function temp_updateTeachers()
    {
        $db = & JFactory::getDbo();

 	$query = '
SELECT L.id, R.teacher 
FROM jos_euniversity_exams_list as L
LEFT JOIN jos_euniversity_exams_tickets as T ON L.ticket=T.id
LEFT JOIN jos_euniversity_users_students_profile as P ON L.student=P.ref
LEFT JOIN jos_euniversity_cat_teachers_rup_students as RS ON P.ref=RS.student
LEFT JOIN jos_euniversity_cat_teachers_rup as R 
ON RS.doc_ref=R.ref
and R.year=1920
and R.discipline=T.discipline
and R.lang=T.lang
and R.type_schedule="Экзамен"
and R.control_exam=1
and R.control_exam_type="ОТ"
and R.apply=1
and R.active=1
WHERE 
result = ""
AND R.ref IS NOT NULL
GROUP BY L.id, R.teacher
';
/*echo "<pre>";
echo $query;
echo "</pre>";*/
	
	$db->setQuery($query);
	$arr = $db->loadAssocList();

	foreach ($arr as $el)
	{
	    if (!empty($el['teacher']))
	    {
	    $query = 'UPDATE #__euniversity_exams_list
SET result='.$db->quote(serialize(array('teacher'=>$el['teacher'], 'code'=>$this->encode_opentest($el['id'])))).'
WHERE id="'.$el['id'].'"';

/*echo "<pre>";
echo $query;
echo "</pre>";*/
	
		$db->setQuery($query);
		$db->query();
    		if($db->getErrorNum()) 
		{
    		    JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
    		}
	    }
	}
    }
    
    public function temp_updateTeachersNull()
    {
        $db = & JFactory::getDbo();

 	$query = '
SELECT L.id, R.teacher 
FROM jos_euniversity_exams_list as L
LEFT JOIN jos_euniversity_exams_tickets as T ON L.ticket=T.id
LEFT JOIN jos_euniversity_users_students_profile as P ON L.student=P.ref
LEFT JOIN jos_euniversity_cat_teachers_rup_students as RS ON P.ref=RS.student
LEFT JOIN jos_euniversity_cat_teachers_rup as R 
ON RS.doc_ref=R.ref
and R.year=1920
and R.discipline=T.discipline
and R.lang=T.lang
and R.type_schedule="Экзамен"
and R.control_exam=1
and R.control_exam_type="ОТ"
and R.apply=1
and R.active=1
WHERE 
(result = ""
OR calculated=0
OR correct=0)
AND R.ref IS NOT NULL
GROUP BY L.id, R.teacher
';
/*echo "<pre>";
echo $query;
echo "</pre>";*/
	
	$db->setQuery($query);
	$arr = $db->loadAssocList();

	foreach ($arr as $el)
	{
	    if (!empty($el['teacher']))
	    {
	    $query = 'UPDATE #__euniversity_exams_list
SET result='.$db->quote(serialize(array('teacher'=>$el['teacher'], 'code'=>$this->encode_opentest($el['id'])))).'
WHERE id="'.$el['id'].'"';

/*echo "<pre>";
echo $query;
echo "</pre>";*/
	
		$db->setQuery($query);
		$db->query();
    		if($db->getErrorNum()) 
		{
    		    JError::raiseError('10032', $db->getErrorNum() . ': ' . $db->getErrorMsg());
    		}
	    }
	}
    }
    

}
