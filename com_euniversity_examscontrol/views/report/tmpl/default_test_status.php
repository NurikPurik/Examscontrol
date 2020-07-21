<?php defined('_JEXEC') or die;
$i = 1;
$jfiltr = JRequest::getVar('jfiltr', array(), 'post', 'array');
$jfiltr['date'] = empty($jfiltr) ? JFactory::getDate()->toFormat('%Y-%m-%d') : (empty($jfiltr['date']) ? null : $jfiltr['date']);

$query = $this->_db->getQuery(true);
$query->from('#__euniversity_cat_teachers_rup_students as r');
$query->leftjoin('#__euniversity_cat_teachers_rup as d on d.ref = r.doc_ref');
$query->leftjoin('#__euniversity_users_students_profile as p on p.ref = r.student');
$query->leftjoin('#__euniversity_ref_discipline as dis on dis.ref = d.discipline');
$query->leftjoin('#__euniversity_cat_edu_lang as l on l.ref = d.lang');
$query->leftjoin('#__euniversity_cat_edu_specs as s on s.ref = d.spec');
$query->select('CONCAT_WS("-",d.discipline,d.semestr,d.lang,d.spec) as `key`');
$query->select('dis.description as discipline_name');
$query->select('d.semestr');
$query->select('l.description as lang_name');
$query->select('s.description as spec_name');
$query->select('s.code as spec_code');
$query->select('count(p.uid) as all_s');
$query->where('d.active=1');
$query->where('d.control_rk1=1');
if(!empty($jfiltr['date'])){
    $query->where('d.control_rk1_date=' . $this->_db->quote($jfiltr['date']));
}
$query->where('d.control_rk1_type = "КТ"');
$query->where('d.apply=1');
$query->where('p.uid IS NOT NULL');
$query->group('d.discipline');
$query->group('d.semestr');
$query->group('d.lang');
$query->group('d.spec');
$query->order('dis.description');
$query->order('d.semestr');
$query->order('l.description');
$query->order('s.description');
$query->order('s.code');
$this->_db->setQuery($query);
$items = $this->_db->loadObjectList();
if (($this->_db->getErrorNum() != 0)) {
    JError::raiseError('10032', $this->_db->getErrorNum() . ': ' . $this->_db->getErrorMsg());
}
$query->clear();
$query->from('#__euniversity_exams_schedule as e');
$query->leftjoin('#__euniversity_users_students_profile as p on p.uid = e.uid');
$query->select('count(e.id) as all_e');
$query->select('count(if(e.calculated=1 AND e.correct>15,1,null)) as all_50');
$query->select('count(if(e.sdate IS NOT NULL,1,null)) as all_begin');
$query->select('count(if(e.calculated=1,1,null)) as all_finished');
$query->select('CONCAT_WS("-",e.discipline,e.semestr,p.lang,p.specs) as `key`');
if(!empty($jfiltr['date'])){
    $query->where('DATE(e.start)=' . $this->_db->quote($jfiltr['date']));
}
$query->where('e.type=1');
$query->where('p.uid IS NOT NULL');
$query->group('e.discipline');
$query->group('e.semestr');
$query->group('p.lang');
$query->group('p.specs');
$this->_db->setQuery($query);
$edata = $this->_db->loadObjectList('key');

if (($this->_db->getErrorNum() != 0)) {
    JError::raiseError('10032', $this->_db->getErrorNum() . ': ' . $this->_db->getErrorMsg());
}
?>

<h5 class="module-title">Отчет :: посещаемость тестирования</h5>
<p>
    <label for="jfiltr_date" style="font-weight: bold;">Дата:</label>
    <input id="jfiltr_date" type="text" name="jfiltr[date]" style="margin:0 10px;"
           value="<?php echo $jfiltr['date'];?>"/>
    <button>Показать</button>
</p>
<?php if(!empty($items)):?>
<table class="zebra">
    <thead>
    <tr>
        <th style="width: 30px;">№</th>
        <th>Дисциплина</th>
        <th>Семестр</th>
        <th>Язык</th>
        <th style="width: 250px;">Специальность</th>
        <th>РУП / Е-ALL / E-50 / E-BEGIN / E-FINISHED</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
    <tr>
        <td><?php echo $i++;?></td>
        <td><?php echo $item->discipline_name;?></td>
        <td><?php echo $item->semestr;?></td>
        <td><?php echo $item->lang_name;?></td>
        <td><?php echo '[' . $item->spec_code . '] ' . $item->spec_name;?></td>
        <td>
            <?php echo $item->all_s . ' / ' .
            (!empty($edata[$item->key]) ? $edata[$item->key]->all_e : 0) . ' / ' .
            (!empty($edata[$item->key]) ? $edata[$item->key]->all_50 : 0) . ' / ' .
            (!empty($edata[$item->key]) ? $edata[$item->key]->all_begin : 0) . ' / ' .
            (!empty($edata[$item->key]) ? $edata[$item->key]->all_finished : 0);?>
        </td>
    </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php else : ?>
<p>Ничего не найдено</p>
<?php endif;?>

