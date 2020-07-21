<?php defined('_JEXEC') or die; ?>
<?php JHtml::script('schedule.js', 'components/com_euniversity_examscontrol/assets/js/'); ?>
<?php $i = 1; ?>
<h3 class="module-title">Контрольный лист студента</h3>
<div style="border: 1px solid #666;">
    <label style="display: block; float: left; width: 100px;">Студент:</label> <?php echo $this->listTests->student;?><br/>
    <label style="display: block; float: left; width: 100px;">ID:</label> <?php echo $this->listTests->code;?><br/>
<br/>
    <label
        style="display: block; float: left; width: 100px;">Дисциплина:</label> <?php echo $this->listTests->discipline_name;?>
<br/>
    <label style="display: block; float: left; width: 100px;">Семестр:</label> <?php echo $this->listTests->semestr;?>
<br/>
    <label style="display: block; float: left; width: 100px;">Тип:</label> <?php echo $this->listTests->type;?><br/>
    <label style="display: block; float: left; width: 100px;">Статус:</label> <?php echo $this->listTests->status;?>
<br/>
    <label style="display: block; float: left; width: 100px;">Доступен
        с:</label> <?php echo substr($this->listTests->start, 0, -3);?><br/>
    <label
        style="display: block; float: left; width: 100px;">Начало:</label> <?php echo substr($this->listTests->sdate, 0, -3);?>
<br/>
    <label
        style="display: block; float: left; width: 100px;">Конец:</label> <?php echo substr($this->listTests->edate, 0, -3);?>
<br/>
    <label
        style="display: block; float: left; width: 100px;">Подсчитан:</label> <?php echo $this->listTests->calculated;?>
<br/>
    <label
        style="display: block; float: left; width: 100px;">Правильных:</label> <?php echo $this->listTests->correct;?>
<br/>
    <label
        style="display: block; float: left; width: 100px;">Добавлено:</label> <?php echo $this->listTests->correct_plus;?>
<br/>
    <label
        style="display: block; float: left; width: 100px;">Бал:</label> <?php echo sprintf("%.1f", $this->listTests->mark);?>
<br/>
    <label style="display: block; float: left; width: 100px;">EID:</label> <?php echo $this->listTests->id;?><br/>
    <label style="display: block; float: left; width: 100px;">UID:</label> <?php echo $this->listTests->uid;?><br/>
</div>
<br/>
<?php if (!empty($this->listTests->result)): ?>
<style type="text/css">
    .correct {
        background-color: green;
        color: white;
        font-weight: bold;
    }

    .correct_border {
        border: 3px solid green !important;
        font-weight: bold;
        color: green;
    }

    .incorrect {
        background-color: red;
        color: white;
        font-weight: bold;
    }
</style>
<table class="zebra">
    <thead>
    <tr>
        <th>№</th>
        <th>Вопрос</th>
        <th>Вариант №1</th>
        <th>Вариант №2</th>
        <th>Вариант №3</th>
        <th>Вариант №4</th>
        <th>Вариант №5</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
        <?php foreach (unserialize($this->listTests->result) as $test): ?>
    <tr<?php echo empty($test['a']) ? ' style="background-color: yellow;"' : null;?>>
        <td><?php echo $i++;?></td>
        <td><strong><?php echo $test['q'];?></strong></td>
        <?php $class = array();?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a0'] == $test['c'] ? "correct_border" : null) : null;?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a0'] == $test['a'] ? "incorrect" : null) : null;?>
        <?php $class[] = $test['a'] == $test['c'] ? ($test['a0'] == $test['a'] ? "correct" : null) : null;?>
        <td<?php echo !empty($class) ? ' class="' . implode(' ', $class) . '"' : null;?>><?php echo $test['a0']?></td>
        <?php $class = array();?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a1'] == $test['c'] ? "correct_border" : null) : null;?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a1'] == $test['a'] ? "incorrect" : null) : null;?>
        <?php $class[] = $test['a'] == $test['c'] ? ($test['a1'] == $test['a'] ? "correct" : null) : null;?>
        <td<?php echo !empty($class) ? ' class="' . implode(' ', $class) . '"' : null;?>><?php echo $test['a1']?></td>
        <?php $class = array();?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a2'] == $test['c'] ? "correct_border" : null) : null;?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a2'] == $test['a'] ? "incorrect" : null) : null;?>
        <?php $class[] = $test['a'] == $test['c'] ? ($test['a2'] == $test['a'] ? "correct" : null) : null;?>
        <td<?php echo !empty($class) ? ' class="' . implode(' ', $class) . '"' : null;?>><?php echo $test['a2']?></td>
        <?php $class = array();?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a3'] == $test['c'] ? "correct_border" : null) : null;?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a3'] == $test['a'] ? "incorrect" : null) : null;?>
        <?php $class[] = $test['a'] == $test['c'] ? ($test['a3'] == $test['a'] ? "correct" : null) : null;?>
        <td<?php echo !empty($class) ? ' class="' . implode(' ', $class) . '"' : null;?>><?php echo $test['a3']?></td>
        <?php $class = array();?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a4'] == $test['c'] ? "correct_border" : null) : null;?>
        <?php $class[] = $test['a'] != $test['c'] ? ($test['a4'] == $test['a'] ? "incorrect" : null) : null;?>
        <?php $class[] = $test['a'] == $test['c'] ? ($test['a4'] == $test['a'] ? "correct" : null) : null;?>
        <td<?php echo !empty($class) ? ' class="' . implode(' ', $class) . '"' : null;?>><?php echo $test['a4']?></td>
    </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php else : ?>
<div class="box-hint">Пустой тест</div>
<?php endif; ?>

