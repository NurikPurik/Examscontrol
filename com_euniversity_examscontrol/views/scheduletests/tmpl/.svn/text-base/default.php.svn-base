<?php defined('_JEXEC') or die; ?>
<?php $i = 1; ?>
<form class="box" name="list" action="index.php" method="post">
    <p>
        <label for="jfiltr_login" style="font-weight: bold;">Поиск:</label>
        <input id="jfiltr_login" type="text" name="login" placeholder="Код студента"
               style="margin:0 10px; width: 200px;" value="<?php echo JRequest::getVar('login', null);?>"/>
        <input type="hidden" name="ologin" value="<?php echo JRequest::getVar('login', null);?>"/>
        <button
            onclick="javascript: document.forms.list.view.value = 'scheduletests';document.forms.list.task.value = '';document.forms.list.layout.value = '';document.forms.list.eid.value = '';document.forms.list.submit(); return false;">
            Показать
        </button>
    </p>
    <?php if (!empty($this->listExams)): ?>
    <h3 class="module-title"><?php echo $this->listExams[0]->student;?> :: Расписание электронного тестирования</h3>
    <hr/>
    <table class="zebra">
        <thead>
        <tr>
            <th>№</th>
            <th>Семестр</th>
            <th>Тип</th>
            <th>Дисциплина</th>
            <th>Дата</th>
            <th>Результат</th>
            <th>Статус</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($this->listExams as $exam): ?>
        <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $exam->semestr;?></td>
            <td><?php echo !empty($this->types[$exam->type-1]) ? $this->types[$exam->type-1] : "Неизвестно";?></td>
            <td><span class="hasTip" title="EID: <?php echo $exam->id;?>"><?php echo $exam->discipline_name;?></span></td>
            <td><?php echo substr($exam->start, 0, -3);?></td>
            <td><?php echo ($exam->correct != 0) ? '<strong>' . sprintf("%.1f", $exam->correct) . '</strong>' : 0;?></td>
            <td><?php echo $this->getStatus($exam);?></td>
            <td style="white-space: nowrap;">
                <?php if ($this->lib_access->AccessCheck('scheduletests_list', 'w')): ?>
                <span class="hasTip button32 info32" title="Результат"
                      onclick="javascript: document.forms.list.setAttribute('target', '_blank');document.forms.list.view.value = 'scheduletests';document.forms.list.task.value = '';document.forms.list.layout.value = 'info';document.forms.list.eid.value = '<?php echo $exam->id;?>';document.forms.list.submit(); return false;">Результат</span>
                <span class="hasTip button32 refresh32" title="Пересчитать"
                      onclick="javascript: if(confirm('Вы уверены что хотите пересчитать результат контроля?')) {document.forms.list.setAttribute('target', '_self');document.forms.list.view.value = '';document.forms.list.task.value = 'manage.recalculate';document.forms.list.layout.value = '';document.forms.list.eid.value = '<?php echo $exam->id;?>';document.forms.list.submit();} return false;">Пересчитать</span>
                <span class="hasTip button32 remove32" title="Очистить"
                      onclick="javascript: if(confirm('Вы уверены что хотите обнулить контроль?')) {document.forms.list.setAttribute('target', '_self');document.forms.list.view.value = '';document.forms.list.task.value = 'manage.clear';document.forms.list.layout.value = 'info';document.forms.list.eid.value = '<?php echo $exam->id;?>';document.forms.list.submit();} return false;">Отчистить</span>
                <span class="hasTip button32 purge32" title="Пересдача"
                      onclick="javascript: return false;">Пересдача</span>
                <?php endif;?>
            </td>
        </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <?php echo JHtml::_('form.token'); ?>
    <?php else: ?>
    <tr>
        <div class="box-hint">По вашему запросу нечего не найдено</div>
    </tr>
    <?php endif;?>
    <input type="hidden" name="option" value="com_euniversity_examscontrol"/>
    <input type="hidden" name="view" value="scheduletests"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="layout" value=""/>
    <input type="hidden" name="eid" value=""/>

</form>

