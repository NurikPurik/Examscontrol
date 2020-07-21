<?php defined('_JEXEC') or die; ?>
<?php JHtml::script('create.js', 'components/com_euniversity_examscontrol/assets/js/'); ?>
<?php $i = 1; ?>
<?php $date = new JDate('now'); ?>
<form class="box" name="list" action="index.php" method="post">
    <div class="clearfix">
        <fieldset class="float-left">
            <label for="date" style="font-weight: bold;">Дата:</label>
            <?php echo JHtml::calendar($this->date, 'date', 'date', '%Y-%m-%d', 'readonly="readonly"');?>
<!--            <button onclick="javascript: document.getElementById('date').value='Все'">Все</button> -->
        </fieldset>
        <fieldset class="float-left" style="height: 32px;">
            <strong>Тип: </strong>
            <label for="type-rk1">РК1</label><input type="radio" name="type" id="type-rk1" value="1"<?php echo $this->type==1 ? ' checked="checked"' : null;?>/>
            <label for="type-rk2">РК2</label><input type="radio" name="type" id="type-rk2" value="2"<?php echo $this->type==2 ? ' checked="checked"' : null;?>/>
            <label for="type-exam">Экзамен</label><input type="radio" name="type" id="type-exam" value="3"<?php echo $this->type==3 ? ' checked="checked"' : null;?>/>
        </fieldset>
        <fieldset class="float-left" style="height: 32px;">
            <strong>Полугодие: </strong>
            <label for="half-1">Первое</label><input type="radio" name="half" id="half-1" value="1"<?php echo $this->half==1 ? ' checked="checked"' : null;?>/>
            <label for="half-2">Второе</label><input type="radio" name="half" id="half-2" value="0"<?php echo $this->half==0 ? ' checked="checked"' : null;?>/>
        </fieldset>
        <button class="float-left" style="margin: 30px 0 0 30px"
                onclick="javascript: document.forms.list.layout.value = '';document.forms.list.discipline.value = '';document.forms.list.semestr.value = '';document.forms.list.lang.value = '';document.forms.list.submit(); return false;">
            Показать
        </button>
    </div>
    <h3 class="module-title">Назначение контроля</h3>
    <table class="zebra">
        <thead>
        <tr>
            <th>#</th>
            <th>Дисциплина</th>
            <th>Семестр</th>
            <th>Язык</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($this->listDocs)): ?>
            <?php foreach ($this->listDocs as $doc): ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td>
                    <a href="#"
                       onclick="javascript: document.forms.list.layout.value = 'students';document.forms.list.discipline.value = '<?php echo $doc->discipline;?>';document.forms.list.semestr.value = '';document.forms.list.lang.value = '';document.forms.list.submit(); return false;">
                        <?php echo $doc->discipline_name;?>
                    </a>
                </td>
                <td>
                    <a href="#"
                       onclick="javascript: document.forms.list.layout.value = 'students';document.forms.list.discipline.value = '<?php echo $doc->discipline;?>';document.forms.list.semestr.value = '<?php echo $doc->semestr;?>';document.forms.list.lang.value = '';document.forms.list.submit(); return false;">
                        <?php echo $doc->semestr;?>
                    </a>
                </td>
                <td>
                    <a href="#"
                       onclick="javascript: document.forms.list.layout.value = 'students';document.forms.list.discipline.value = '<?php echo $doc->discipline;?>';document.forms.list.semestr.value = '<?php echo $doc->semestr;?>';document.forms.list.lang.value = '<?php echo $doc->lang;?>';document.forms.list.submit(); return false;">
                        <?php echo $doc->lang_name;?>
                    </a>
                </td>
            </tr>
                <?php endforeach; ?>
            <?php else: ?>
        <tr>
            <td colspan="4">
                <div class="box-hint">По вашему запросу нечего не найдено</div>
            </td>
        </tr>
            <?php endif;?>
        </tbody>
    </table>
    <input type="hidden" name="option" value="com_euniversity_examscontrol"/>
    <input type="hidden" name="view" value="createtests"/>
    <input type="hidden" name="layout" value=""/>
    <input type="hidden" name="discipline" value=""/>
    <input type="hidden" name="semestr" value=""/>
    <input type="hidden" name="lang" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>

