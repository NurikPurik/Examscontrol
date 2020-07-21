<?php defined('_JEXEC') or die;
$students = (object) array();

$query = $this->_db->getQuery(true);
$query->from('#__euniversity_exams_schedule as e');
$query->leftjoin('#__euniversity_users_students_profile as p on e.uid=p.uid');
$query->leftjoin('#__euniversity_cat_edu_facultets as f on f.ref = p.facultet');
$query->leftjoin('#__euniversity_cat_other_finance as of on of.ref = p.finance');
$query->leftjoin('#__euniversity_cat_students_forms as fo on fo.ref = p.form');
$query->leftjoin('#__euniversity_ref_discipline as r_dis on r_dis.ref = e.discipline');
$query->leftjoin('#__euniversity_cat_edu_lang as l on l.ref = p.lang');
$query->leftjoin('#__euniversity_cat_edu_specs as s on s.ref = p.specs');
$query->leftjoin('#__euniversity_exams_tests as t on t.id = e.tid');
$query->select('e.id as eid');
$query->select('e.type');
$query->select('e.variant');
$query->select('f.description as facultet');
$query->select('s.code as spec_code');
$query->select('s.description as spec_name');
$query->select('e.semestr');
$query->select('l.description as lang_name');
$query->select('of.description as finance');
$query->select('fo.description as form');
$query->select('e.uid as student');
$query->select('p.description as student_name');
$query->select('e.discipline as discipline_id');
$query->select('r_dis.description as discipline_name');
$query->select('0 as tk1');
$query->select('max(CASE WHEN e.type = 1 THEN (CASE WHEN e.variant=1 THEN e.correct*100/30+e.correct_plus ELSE e.correct+e.correct_plus END) ELSE 0 END) as rk1');
$query->select('0 as tk2');
$query->select('max(CASE WHEN e.type = 2 THEN (CASE WHEN e.variant=1 THEN e.correct*100/30+e.correct_plus ELSE e.correct+e.correct_plus END) ELSE 0 END) as rk2');
$query->select('max(CASE WHEN e.type = 3 THEN (CASE WHEN e.variant=1 THEN e.correct*100/30+e.correct_plus ELSE e.correct+e.correct_plus END) ELSE 0 END) as exam');
$query->select('e.variant = 1');
$query->order('f.description');
$query->order('s.code');
$query->order('s.description');
$query->order('e.semestr');
$query->order('l.description');
$query->order('of.description');
$query->order('fo.description');
$query->order('p.description');
$query->order('discipline_name');
$query->group('e.uid');
$query->group('discipline_id');

$query->where('mod(e.semestr,2)=0');

//$query->where('p.uid=2704');

$this->_db->setQuery($query);
$exams = $this->_db->loadObjectList();
if (($this->_db->getErrorNum() != 0)) {
    JError::raiseError('10032', $this->_db->getErrorNum() . ': ' . $this->_db->getErrorMsg());
}

foreach($exams as $item){
    $students->{$item->student}->{$item->discipline_id} = $item;
}

$query->clear();
$query->from('#__euniversity_journal_main_ratings as r');
$query->leftjoin('#__euniversity_journal_main_list as l on r.lid=l.id');
$query->leftjoin('#__euniversity_users_students_profile as p on r.student=p.ref');
$query->leftjoin('#__euniversity_cat_edu_facultets as f on f.ref = p.facultet');
$query->leftjoin('#__euniversity_cat_other_finance as of on of.ref = p.finance');
$query->leftjoin('#__euniversity_cat_students_forms as fo on fo.ref = p.form');
$query->leftjoin('#__euniversity_cat_edu_lang as ln on ln.ref = p.lang');
$query->leftjoin('#__euniversity_cat_edu_specs as s on s.ref = p.specs');
$query->leftjoin('#__euniversity_ref_discipline as r_dis on r_dis.ref = l.discipline');
$query->select('f.description as facultet');
$query->select('s.code as spec_code');
$query->select('s.description as spec_name');
$query->select('l.semestr');
$query->select('ln.description as lang_name');
$query->select('of.description as finance');
$query->select('fo.description as form');
$query->select('p.uid as student');
$query->select('p.description as student_name');
$query->select('l.discipline as discipline');
$query->select('r_dis.description as discipline_name');
$query->select('max(CASE WHEN l.type = 1  THEN IF(r.rate<>"Н",r.rate,0) ELSE 0 END) as tk1');
$query->select('max(CASE WHEN l.type = 7  THEN IF(r.rate<>"Н",r.rate,0) ELSE 0 END) as rk1');
$query->select('max(CASE WHEN l.type = 4  THEN IF(r.rate<>"Н",r.rate,0) ELSE 0 END) as tk2');
$query->select('max(CASE WHEN l.type = 10 THEN IF(r.rate<>"Н",r.rate,0) ELSE 0 END) as rk2');
$query->select('max(CASE WHEN l.type = 13 THEN IF(r.rate<>"Н",r.rate,0) ELSE 0 END) as exam');
$query->select('l.discipline as discipline_id');
$query->group('p.uid');
$query->group('l.discipline');

$query->where('mod(l.semestr,2)=0');

//$query->where('p.uid=2704');

$this->_db->setQuery($query);
$exams = $this->_db->loadObjectList();
if (($this->_db->getErrorNum() != 0)) {
    JError::raiseError('10032', $this->_db->getErrorNum() . ': ' . $this->_db->getErrorMsg());
}

foreach($exams as $item){
    if(empty($students->{$item->student}->{$item->discipline_id})){
	$students->{$item->student}->{$item->discipline_id} = $item;
    }else{
	$students->{$item->student}->{$item->discipline_id}->tk1  = max((float)$item->tk1,(float)$students->{$item->student}->{$item->discipline_id}->tk1);
	$students->{$item->student}->{$item->discipline_id}->rk1  = max((float)$item->rk1,(float)$students->{$item->student}->{$item->discipline_id}->rk1);
	$students->{$item->student}->{$item->discipline_id}->tk2  = max((float)$item->tk2,(float)$students->{$item->student}->{$item->discipline_id}->tk2);
	$students->{$item->student}->{$item->discipline_id}->rk2  = max((float)$item->rk2,(float)$students->{$item->student}->{$item->discipline_id}->rk2);
	$students->{$item->student}->{$item->discipline_id}->exam = max((float)$item->exam,(float)$students->{$item->student}->{$item->discipline_id}->exam);
    }
}

?>

<h3 class="module-title">Сводная ведомость</h3>
<table class="zebra" id="results">
    <thead>
    <tr>
        <th style="">Факультет</th>
        <th style="">Специальность</th>
        <th style="">Семестр</th>
        <th style="">Отделение</th>
        <th style="">Финансирование</th>
        <th style="">Форма</th>
        <th style="">Студент</th>
        <!--        <th style="">Д(#)</th>-->
        <th style="">Дисциплина</th>
        <th style="">TК1</th>
        <th style="">РК1</th>
        <!--        <th style="">РК1(*)</th>-->
        <th style="">TК2</th>
        <th style="">РК2</th>
        <!--        <th style="">РК2(*)</th>-->
        <th style="">Экзамен</th>
        <!--        <th style="">Э(*)</th>-->
    </tr>
    </thead>
    <tbody>
    <?php foreach ($students as $student): ?>
    <?php foreach ($student as $exam): ?>
    <tr>
        <td><?php echo $exam->facultet?></td>
        <td><?php echo '[' . $exam->spec_code . '] ' . $exam->spec_name;?></td>
        <td><?php echo $exam->semestr;?></td>
        <td><?php echo $exam->lang_name;?></td>
        <td><?php echo $exam->finance?></td>
        <td><?php echo $exam->form?></td>
        <td><?php echo $exam->student_name;?></td>
        <td><?php echo $exam->discipline_name;?></td>
        <td><?php echo number_format((float)$exam->tk1,2);?></td>
        <td><?php echo number_format((float)$exam->rk1,2);?></td>
        <td><?php echo number_format((float)$exam->tk2,2);?></td>
        <td><?php echo number_format((float)$exam->rk2,2);?></td>
        <td><?php echo number_format((float)$exam->exam,2);?></td>
    </tr>
    <?php endforeach;?>
    <?php endforeach;?>
    </tbody>
</table>


