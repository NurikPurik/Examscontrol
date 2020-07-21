<?php defined('_JEXEC') or die; ?>
<?php JHtml::script('create.js', 'components/com_euniversity_examscontrol/assets/js/'); ?>
<?php $i = 1; ?>
<?php $date = new JDate('now'); ?>
<form class="box" name="list" action="<?php echo JURI::getInstance();?>" method="post">
    <div class="clearfix">
        <fieldset class="float-left">
            <label for="date" style="font-weight: bold;">Дата:</label>
            <?php echo JHtml::calendar(JRequest::getString('date', JFactory::getDate()->toFormat('%Y-%m-%d')), 'date', 'date', '%Y-%m-%d', 'readonly="readonly"');?>
        </fieldset>
        <fieldset class="float-left" style="height: 32px;">
            <strong>Полугодие: </strong>
            <label for="half-1">Первое</label><input type="radio" name="half" id="half-1" value="1"<?php echo JRequest::getString('half', 0)==1 ? ' checked="checked"' : null;?> onchange="this.form.submit()"/>
            <label for="half-2">Второе</label><input type="radio" name="half" id="half-2" value="0"<?php echo JRequest::getString('half', 0)==0 ? ' checked="checked"' : null;?> onchange="this.form.submit()"/>
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
            <th style="width:100%;">Дисциплина</th>
            <th>Семестр</th>
            <th>Отделение</th>
            <th style="width: 142px;"></th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($this->listDocs) > 0): ?>
            <?php foreach ($this->listDocs as $doc): ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td>
                    <?php echo $doc->discipline_name;?>
                </td>
                <td>
                    <?php echo $doc->semestr;?>
                </td>
                <td>
                    <?php echo $doc->lang_name;?>
                </td>
                <td>
                    <button
                        onclick="javascript: document.forms.list.layout.value = 'liststreams';document.forms.list.discipline.value = '<?php echo $doc->discipline;?>';document.forms.list.semestr.value = '<?php echo $doc->semestr;?>';document.forms.list.lang.value = '<?php echo $doc->lang;?>';document.forms.list.submit(); return false;">
                        Потоки
                    </button>
                </td>

            </tr>
                <?php endforeach; ?>
            <?php else : ?>
        <tr>
            <td colspan="5">
                <div class="box-hint">По вашему запросу нечего не найдено</div>
            </td>
        </tr>
            <?php endif;?>
        </tbody>
    </table>
    <input type="hidden" name="option" value="com_euniversity_examscontrol"/>
    <input type="hidden" name="view" value="createtickets"/>
    <input type="hidden" name="layout" value=""/>
    <input type="hidden" name="discipline" value=""/>
    <input type="hidden" name="semestr" value=""/>
    <input type="hidden" name="lang" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>

