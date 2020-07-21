<?php defined('_JEXEC') or die;
$i = 1;
$jfiltr = JRequest::getVar('jfiltr', array(), 'post', 'array');
$jfiltr['date'] = empty($jfiltr) ? JFactory::getDate()->toFormat('%Y-%m-%d') : (empty($jfiltr['date']) ? null : $jfiltr['date']);

$query = $this->_db->getQuery(true);
$query->from('#__euniversity_exams_schedule as e');
$query->leftjoin('#__euniversity_users_students_profile as p on p.uid = e.uid');
$query->leftjoin('#__euniversity_ref_discipline as r_dis on r_dis.ref = e.discipline');
$query->leftjoin('#__euniversity_cat_discipline as c_dis on c_dis.id = e.discipline');
$query->select('e.id');
$query->select('e.semestr');
$query->select('(case when e.type=1 then "РК1" when e.type=2 then "РК2" when e.type=3 then "Экзамен" end) as type');
$query->select('(CASE WHEN c_dis.description IS NULL THEN (CASE WHEN r_dis.description IS NULL THEN e.id ELSE r_dis.description END) ELSE c_dis.description END) as discipline_name');
$query->select('p.description as student');
$query->select('(case when e.sdate IS NULL     AND e.calculated=0 AND e.edate IS NULL     AND e.status=1 AND e.result IS NULL     then 1
                      when e.sdate IS NOT NULL AND e.calculated=0 AND e.edate IS NULL     AND e.status=2 AND e.result IS NOT NULL then 2
                      when e.sdate IS NOT NULL AND e.calculated=1 AND e.edate IS NOT NULL AND e.status=3 AND e.result IS NOT NULL then 3
                      else 4 end) as status');
$query->select('e.start');
$query->select('e.sdate');
$query->where('e.variant=1');
$query->where('status=2');
if (!empty($jfiltr['date'])) {
    $query->where('DATE(e.start)=' . $this->_db->quote($jfiltr['date']));
}
$this->_db->setQuery($query);
$items = $this->_db->loadObjectList();
if (($this->_db->getErrorNum() != 0)) {
    JError::raiseError('10032', $this->_db->getErrorNum() . ': ' . $this->_db->getErrorMsg());
}
?>

<h5 class="module-title">Отчет :: активные тестирования</h5>
<p>
    <label for="jfiltr_date" style="font-weight: bold;">Дата:</label>
    <input id="jfiltr_date" type="text" name="jfiltr[date]" style="margin:0 10px;"
           value="<?php echo $jfiltr['date'];?>"/>
    <button>Показать</button>
</p>

<table class="zebra">
    <thead>
    <tr>
        <th><input type="checkbox" id="checkall"/></th>
        <th style="width: 30px;">№</th>
        <th>Семестр</th>
        <th>Тип</th>
        <th>Дисциплина</th>
        <th>Студент</th>
        <th>Дата</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="7"><span class="button32 status-green32 active" style="float: left;">Завершить</span></td>
    </tr>
    </tfoot>
    <tbody>
    <?php if (!empty($items)): ?>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><input type="checkbox" name="id[]"/></td>
            <td><?php echo $i++;?></td>
            <td><?php echo $item->semestr;?></td>
            <td><?php echo $item->type;?></td>
            <td><?php echo $item->discipline_name;?></td>
            <td><?php echo $item->student;?></td>
            <td>Начало: <?php echo substr($item->start, 0, -3);?><br/>Начал: <?php echo substr($item->sdate, 0, -3);?>
            </td>
        </tr>
            <?php endforeach; ?>
        <?php else : ?>
    <tr>
        <td colspan="4">
            <div class="box-hint">По вашему запросу нечего не найдено</div>
        </td>
    </tr>
        <?php endif;?>
    </tbody>
</table>

