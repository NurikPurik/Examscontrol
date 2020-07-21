<?php defined('_JEXEC') or die;

$query = $this->_db->getQuery(true);
$query->from('#__euniversity_exams_schedule as e');
$query->leftjoin('#__euniversity_ref_discipline as r_dis on r_dis.ref = e.discipline');
$query->leftjoin('#__euniversity_cat_discipline as c_dis on c_dis.id = e.discipline');
$query->select('e.discipline as id');
$query->select('(CASE WHEN c_dis.description IS NULL THEN (CASE WHEN r_dis.description IS NULL THEN e.id ELSE r_dis.description END) ELSE c_dis.description END) as name');
$query->where('e.variant=1');
$query->where('e.type=1');
$query->where('mod(e.semestr,2)=0');
$query->group('e.discipline');
$query->order('name');

$this->_db->setQuery($query);
$disciplines = $this->_db->loadObjectList();
if (($this->_db->getErrorNum() != 0)) {
    JError::raiseError('10032', $this->_db->getErrorNum() . ': ' . $this->_db->getErrorMsg());
}

$query->clear();
$query->from('#__euniversity_exams_schedule as e');
$query->leftjoin('#__euniversity_users_students_profile as p on e.uid=p.uid');
$query->leftjoin('#__euniversity_cat_edu_facultets as f on f.ref = p.facultet');
$query->leftjoin('#__euniversity_cat_edu_specs as s on s.ref = p.specs');
$query->select('e.id');
$query->select('p.description as student');
$query->select('f.description as facultet');
$query->select('s.description as spec_name');
$query->select('s.code as spec_code');
$i=0;
foreach($disciplines as $discipline){
    $query->select('(CASE WHEN e.discipline=' . $this->_db->quote($discipline->id) . ' THEN e.correct*100/30+e.correct_plus END) as discipline' . $i);
    $i++;
}
$query->where('e.variant=1');
$query->where('e.type=1');
$query->where('mod(e.semestr,2)=0');
$query->group('e.uid');
$query->group('e.discipline');
$query->group('e.type');
$query->order('f.description');
$query->order('s.code');
$query->order('s.description');
$query->order('p.description');
$this->_db->setQuery($query);
$marks = $this->_db->loadObjectList();
if (($this->_db->getErrorNum() != 0)) {
    JError::raiseError('10032', $this->_db->getErrorNum() . ': ' . $this->_db->getErrorMsg());
}
?>

<h5 class="module-title">Отчет :: сводная ведомость по тестированию</h5>
<?php if(!empty($disciplines)):?>
<table class="zebra">
    <thead>
    <tr>
        <th rowspan="2">Факультет</th>
        <th rowspan="2">Специальность</th>
        <th rowspan="2">Фио</th>
        <th colspan="<?php echo count($disciplines);?>">Дисциплина</th>
    </tr>
    <tr>
        <?php foreach($disciplines as $discipline):?>
        <td style="border: 1px solid #666;"><?php echo $discipline->name;?></td>
        <?php endforeach;?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($marks as $item): ?>
    <tr>
        <td><?php echo $item->facultet;?></td>
        <td><?php echo '[' . $item->spec_code . ']' . $item->spec_name;?></td>
        <td><?php echo $item->student;?></td>
        <?php for($i=0;$i<count($disciplines);$i++):?>
            <td><?php echo $item->{'discipline' . $i};?></td>
        <?php endfor;?>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php else : ?>
<p>Ничего не найдено</p>
<?php endif;?>
