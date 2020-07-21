<?php defined('_JEXEC') or die; ?>
<?php JHtml::script('createtickets.js', 'components/com_euniversity_examscontrol/assets/js/'); ?>
<?php $i = 1; ?>
<style type="text/css">
    mark{
        text-align: center;
        display: inline-block;
        width: 83px;
        margin: 3px 3px;
        padding:5px 10px;
        background:rgba(0,0,0,0.03);
        border: 1px solid rgba(0,0,0,0.1);
        color:#666;
        white-space:nowrap;
        font-weight:bold;
        font-size:14px;
    }
    mark.button{
        cursor: pointer;
    }
    mark.button:hover{
        background: rgba(228,204,36,0.2);
    }
    mark.button.active{
        background:rgba(81,187,255,0.4);
    }
    .small_button{
        float:right;
        color:#8CBF59;
        background-color:#fffae6;
        padding:0 1px;
        border:1px solid rgba(0,0,0,0.1);
        width: 90px;
        height: 16px;
        line-height: 16px;
        text-align: center;
	font-size: 17px;
	font-family: monospace;
    }
    .get_code{cursor: pointer; color:#666666;}
    .block_code{background-color:#229B9C; color:#fff;}
    .del_code{cursor:pointer; background-color:#ffdd77; color:#666666;}
    .last{background-color:#ffee66; font-weight:bold; color:#000000;}
    .zebra td{
        vertical-align:top;
    }
    .bullet.line{
        margin-left: 25px;
    }
    .hiddenElement{
        display: none;
        visibility: hidden;
    }
    .noselect{-moz-user-select: none; -khtml-user-select: none; user-select: none;}
    .loading{
        content: "";
        background: url(data:image/gif;base64,R0lGODlhEAALAPQAAP///wAAANra2tDQ0Orq6gYGBgAAAC4uLoKCgmBgYLq6uiIiIkpKSoqKimRkZL6+viYmJgQEBE5OTubm5tjY2PT09Dg4ONzc3PLy8ra2tqCgoMrKyu7u7gAAAAAAAAAAACH+GkNyZWF0ZWQgd2l0aCBhamF4bG9hZC5pbmZvACH5BAALAAAAIf8LTkVUU0NBUEUyLjADAQAAACwAAAAAEAALAAAFLSAgjmRpnqSgCuLKAq5AEIM4zDVw03ve27ifDgfkEYe04kDIDC5zrtYKRa2WQgAh+QQACwABACwAAAAAEAALAAAFJGBhGAVgnqhpHIeRvsDawqns0qeN5+y967tYLyicBYE7EYkYAgAh+QQACwACACwAAAAAEAALAAAFNiAgjothLOOIJAkiGgxjpGKiKMkbz7SN6zIawJcDwIK9W/HISxGBzdHTuBNOmcJVCyoUlk7CEAAh+QQACwADACwAAAAAEAALAAAFNSAgjqQIRRFUAo3jNGIkSdHqPI8Tz3V55zuaDacDyIQ+YrBH+hWPzJFzOQQaeavWi7oqnVIhACH5BAALAAQALAAAAAAQAAsAAAUyICCOZGme1rJY5kRRk7hI0mJSVUXJtF3iOl7tltsBZsNfUegjAY3I5sgFY55KqdX1GgIAIfkEAAsABQAsAAAAABAACwAABTcgII5kaZ4kcV2EqLJipmnZhWGXaOOitm2aXQ4g7P2Ct2ER4AMul00kj5g0Al8tADY2y6C+4FIIACH5BAALAAYALAAAAAAQAAsAAAUvICCOZGme5ERRk6iy7qpyHCVStA3gNa/7txxwlwv2isSacYUc+l4tADQGQ1mvpBAAIfkEAAsABwAsAAAAABAACwAABS8gII5kaZ7kRFGTqLLuqnIcJVK0DeA1r/u3HHCXC/aKxJpxhRz6Xi0ANAZDWa+kEAA7AAAAAAAAAAAA) 50% 50% no-repeat;
    }
</style>
<form id="create" class="box" action="<?php echo JURI::getInstance();?>" method="post" enctype="multipart/form-data" >
    <h3 class="module-title">
        <a href="index.php?option=com_euniversity_examscontrol&view=createtickets&date=<?php echo JRequest::getString('date', JFactory::getDate()->toFormat('%Y-%m-%d'));?>">
            Назначение контроля
        </a>
        :: Потоки :: Выбор специальности
    </h3>
    <div class="box-info" style="background-image: none; padding-left: 10px;">
    <h4 class="module-title">

	<?php $rgSearch = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');?>
	<?php $rgReplace = array('Понедельник','Вторник','Среда','Четверг','Пятница','Суббота','Воскресенье');?>

	<b>Дата проведения:</b> <?php echo JRequest::getString('date'); echo " (".str_replace($rgSearch, $rgReplace, date('l', strtotime(JRequest::getString('date')))).")"; ?><br/>
	<b>Дисциплина:</b> <?php echo '['.$this->discipline->code.'] '.$this->discipline->description;?><br/>
	<b>Отделение:</b> <?php echo $this->lang->description;?><br/>
	<b>Семестр:</b> <?php echo JRequest::getInt('semestr');?>
    </h4>
    </div>
    <div class="box-hint" style="background-image: none; padding-left: 10px;">
    <h4 class="module-title">Выбор вопросника</h4>
    <table class="zebra">
        <thead>
        <tr>
            <th>№</th>
            <th><input type="radio" disabled="disabled" /></th>
            <th width="100%">Специальности(потоки) на которые назначался этот вопросник</th>
        </tr>
        </thead>
        <tbody>
        <?php $tests = array('tests'=>array(),'students'=>array());?>
        <?php foreach ($this->listTests as $test): ?>
            <?php $tests['tests'][$test->test_id][$test->spec_key]['spec_name'] = '[' . $test->spec_code . '] ' . $test->spec_name;?>
            <?php $tests['students'][] = $test->student;?>
        <?php endforeach;?>
        <?php foreach ($tests['tests'] as $key => $test): ?>
        <tr>
            <td style="vertical-align: middle;"><?php echo $key;?></td>
            <td style="vertical-align: middle;"><input type="radio" name="qradio" value="<?php echo $key;?>" /></td>
            <td style="text-align: left;">
                <ul class="clearfix">
                    <?php foreach($test as $spec):?>
                    <li style="width: 50%;float:left;display:inline;"><?php echo $spec['spec_name'];?></li>
                    <?php endforeach;?>
                </ul>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td> </td>
            <td><input id="newQradio" type="radio" name="qradio" value="0" checked="true" /></td>
            <td style="font-weight: bold; font-size: 16px; height: 50px;">
                <em>Создать новый</em>&nbsp;&nbsp;&nbsp;<input id="newQ" name="questions" type="file" />
            </td>
        </tr>
        </tfoot>
    </table>
    </div>
    <div class="box-download" style="background-image: none; padding-left: 10px;">
    <h4 class="module-title">Выбор специальностей(потоков)</h4>
    <table class="zebra">
        <thead>
        <tr>
            <th>№</th>
            <th><input id="checkAllBtn" type="checkbox" /></th>
            <th>Специальности(потоки) для которых требуется назначить вопросник</th>
            <th style="width:150px;">Преподаватель</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>
		<?
			
		//print_r($this->listStreams);
		
		?>
        <?php $streams = array();?>
        <?php $year = null;?>
        <?php foreach ($this->listStreams as $key => $stream): ?>
            <?php if(empty($streams[$stream->spec_key][$stream->teacher]['students_count'])) $streams[$stream->spec_key][$stream->teacher]['students_count'] = 0;?>
            <?php if(empty($year)) $year=$stream->year;?>
            <?php $streams[$stream->spec_key][$stream->teacher]['spec_name']      = '[' . $stream->spec_code . '] ' . $stream->spec_name;?>
            <?php $streams[$stream->spec_key][$stream->teacher]['teacher_name']   = $stream->teacher_name;?>
            <?php $streams[$stream->spec_key][$stream->teacher]['students_count']++;?>
            <?php $streams[$stream->spec_key][$stream->teacher]['student'][$stream->student] = $stream->student;?>
            <?php if(!in_array($key, $tests['students'])):?>
            <?php     $streams[$stream->spec_key][$stream->teacher]['students_check'][] = $key;?>
            <?php else:?>
            <?php     $streams[$stream->spec_key][$stream->teacher]['students_uncheck'][] = $key;?>
            <?php endif;?>
        <?php endforeach;?>
        <?php foreach ($streams as $skey => $stream): ?>
        <?php foreach ($stream as $tkey => $streamteacher): ?>
        <tr>
            <?php $test_id = 0;?>
            <?php if(empty($streamteacher['students_check'])) $streamteacher['students_check'] = array();?>
            <td><?php echo $i++;?></td>
            <td>
                <input name="streams[<?php echo $skey . '--' . $tkey;?>]"
                       type="checkbox"
                       value="<?php echo base64_encode(json_encode($streamteacher));?>"<?php echo empty($streamteacher['students_check']) || (count($streamteacher['students_check']) != $streamteacher['students_count']) ? 'disabled="true"' : null;
                ?> />
            </td>
            <td>
                <?php echo $streamteacher['spec_name'];?>
                <?php $students_names = array();?>
                <ul class="zebra hiddenElement noselect" style="color:#666;">
                    <?php $j=1;?>
                    <?php foreach($streamteacher['student'] as $student):?>
                        <?php if(empty($test_id) && !empty($this->listTests[$student]->test_id)): $test_id = $this->listTests[$student]->test_id;endif;?>
                        <li class="clearfix">
                            <?php echo $j++ . '. ';?>
                            [<?php echo $this->listStreams[$student]->student_code;?>]
                            <?php echo $this->listStreams[$student]->student_name;?>
                            <?php if(isset($this->listTests[$student]->status)):?>
                            <?php if(empty($this->listTests[$student]->status)):?>
                                <span class="small_button get_code noselect" id="code_<?php echo $this->listTests[$student]->clear_code;?>">Явка</span>
                            <?php else :?>
                                <span 
                                    class="small_button noselect<?php echo $this->listTests[$student]->status == 1 ? ' del_code' : ' block_code';?>" 
                                    id="code_<?php echo $this->listTests[$student]->clear_code;?>">
                                    <?php echo $this->getModel()->encodeOpenTest($this->listTests[$student]->clear_code);;?>
                                </span>
                            <?php endif;?>
                            <?php endif;?>
                        </li>
                        <?php $students_names[] = $this->listStreams[$student]->student_name;?>
                    <?php endforeach;?>
                </ul>
            </td>
            <td style="text-align: left;">
                <input type="hidden" name="test_id" value="<?php echo $test_id;?>" disabled="true" />
                <input type="hidden" name="students_count" value="<?php echo $streamteacher['students_count'];?>" disabled="true" />
                <input type="hidden" name="teacher_name" value="<?php echo $streamteacher['teacher_name'];?>" disabled="true" />
                <input type="hidden" name="spec_name" value="<?php echo $streamteacher['spec_name'];?>" disabled="true" />
                <input type="hidden" name="stream_key" value="<?php echo $skey . '--' . $tkey;?>" disabled="true" />
                <input type="hidden" name="students_names" value="<?php echo base64_encode(json_encode($students_names));?>" disabled="true" />
                <?php echo $streamteacher['teacher_name'];?>
                </td>
            <td style="text-align: right;">
                <span style="white-space: nowrap;">
                <mark class="button students noselect">Студенты</mark>
                <?php if(empty($streamteacher['students_check'])):?>
                    <mark style="background-color:#8CBF59;color:#fff;">Назначено</mark><br />
                    <mark class="button tickets" style="background-color:#99BFC2;color:#fff;width:197px;">Билеты № <?php echo $test_id; ?></mark>
                <?php elseif(count($streamteacher['students_check']) == $streamteacher['students_count']):?>
                    <mark style="background-color:#DD4B3C;color:#fff;">Не назначено</mark>
                <?php else:?>
                    <mark class="button addon" data-students="<?php echo base64_encode(json_encode($streamteacher['students_check']));?>" data-test="<?php echo $test_id;?>" style="background-color:#6EB0ED;color:#fff;text-decoration:blink;">Доназначить</mark><br />
                    <mark class="button tickets" style="background-color:#99BFC2;color:#fff;width:197px;">Билеты № <?php echo $test_id; ?></mark>
                <?php endif;?>
                </span>
            </td>
        </tr>
        <?php endforeach;?>
        <?php endforeach;?>
        </tbody>
    </table>
    </div>
    <button id="createBtn" style="display:none;" disabled="true">Назначить</button>
    <input type="hidden" name="option" value="com_euniversity_examscontrol" />
    <input type="hidden" name="task" value="manage.createtickets" disabled="true" />
    <input type="hidden" name="layout" value="listtickets" disabled="true" />
    
    <input type="hidden" name="spec" value="" />
    <input type="hidden" name="teacher" value="" />
    <input type="hidden" name="discipline" value="<?php echo JRequest::getString('discipline');?>" />
    <input type="hidden" name="semestr" value="<?php echo JRequest::getInt('semestr');?>" />
    <input type="hidden" name="lang" value="<?php echo JRequest::getString('lang');?>" />
    <input type="hidden" name="date" value="<?php echo JRequest::getString('date');?>" />
    <input type="hidden" name="year" value="<?php echo $year;?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>

<form id="addon" action="<?php echo JURI::getInstance();?>" method="post" enctype="multipart/form-data" >
    <input type="hidden" name="option" value="com_euniversity_examscontrol" />
    <input type="hidden" name="task" value="manage.addontickets" />
    <input type="hidden" name="students" value="" />
    <input type="hidden" name="test_id" value="" />
    <input type="hidden" name="date" value="<?php echo JRequest::getString('date');?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>