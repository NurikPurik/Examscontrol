<?php defined('_JEXEC') or die; ?>
<?php JHtml::script('createtickets.js', 'components/com_euniversity_examscontrol/assets/js/'); ?>
<?php $i = 1; ?>
<form id="create" class="box" action="<?php echo JURI::getInstance();?>" method="post" enctype="multipart/form-data" >
    <h3 class="module-title">
        <a href="index.php?option=com_euniversity_examscontrol&view=createtickets&date=<?php echo $this->streams->date;?>">
            Назначение контроля
        </a>
        :: Потоки :: Выбор Специальности :: Билеты
    </h3>

    <div class="box-download clearfix" style="background-image: none; padding-left: 10px;">
        <ul style="list-style-type: none; padding: 0; margin: 0;">
            <li style="float: left;">
                <strong>Учебный год:</strong> <?php echo 20 . substr_replace($this->streams->year, '-20', 2, 0);?><br/>
                <strong>Дисциплина:</strong> [<?php echo $this->streams->discipline->code;?>] <?php echo $this->streams->discipline->description;?><br/>
                <strong>Отделение:</strong> <?php echo $this->streams->lang->description;?><br/>
                <strong>Семестр:</strong> <?php echo $this->streams->semestr;?><br />
                <strong>Полугодие:</strong> <?php echo $this->streams->semestr%2 ? 'Зимняя сессия' : 'Летняя сессия';?><br/>
            </li>
            <li style="float: right;">
                <strong>Дата проведения:</strong> <?php echo $this->streams->date;?><br/>
                <strong>Протокол №:</strong>
                <input onchange="jQuery('#chData').val(1);" type="text" name="protocol_id" value="<?php echo $this->streams->protocol_id?>"/>
                от
                <?php echo JHtml::calendar($this->streams->protocol_date, 'protocol_date', 'protocol_date', '%Y-%m-%d', 'readonly="readonly" onchange="jQuery(\'#chData\').val(1);"');?><br />
                <strong>Вопросник:</strong> <?php echo $this->streams->test_id;?><br/>
            </li>
        </ul>
    </div>
    <?php foreach($this->streams->streams as $key => $stream):?>
    <div class="box-info clearfix" style="background-image: none; padding-left: 10px; text-align: center;">
        <strong><?php echo $stream->spec_name;?></strong>
        <hr />
        <ul style="list-style-type: none; padding: 0; margin: 0;">
            <li style="float: left;">
                <strong>Преподаватель:</strong> <?php echo $stream->teacher_name;?><br/>
            </li>
            <li style="float: right;">
                <strong>Количество билетов:</strong> <input onchange="jQuery('#chData').val(1);" type="text" name="tickets[<?php echo $key;?>]" value="<?php echo $stream->students_count;?>" />
            </li>
        </ul>
    </div>
    <?php endforeach;?>
    <button>Распечатать</button>
    <input type="hidden" name="option" value="com_euniversity_examscontrol" />
    <input type="hidden" name="format" value="raw"/>
    <input type="hidden" name="data_changed" id="chData" value="0" />
    <input type="hidden" name="uid" value="<?php echo $this->streams->uid;?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>

