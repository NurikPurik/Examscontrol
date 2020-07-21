<?php defined('_JEXEC') or die; ?>
<?php JHtml::script('createtest.js', 'components/com_euniversity_examscontrol/assets/js/'); ?>
<form class="box" name="list" action="index.php" method="post">
    <h3 class="module-title">Назначение контроля
        (<?php echo $this->type_description[$this->type] . ', ' . $this->half_description[$this->half]?>) :: Выбор
        студентов</h3>

    <div class="clearfix">
        <fieldset class="float-left"  style="height: 32px;">
            <label for="teacher" style="font-weight: bold;">Преподаватель:</label>
            <select
                   id="sTeacher"
                   name="teacher"
                   style=" margin:0 10px; width: 100px;">
                   <option value="0">Все</option>
                   <?php foreach($this->listTeachers as $teacher):?>
                       <option value="<?php echo $teacher->ref;?>"><?php echo $teacher->description;?></option>
                   <?php endforeach;?>
            </select>
        </fieldset>
        <fieldset class="float-left"  style="height: 32px;">
            <label for="questionarie" style="font-weight: bold;">ID Вопросника:</label>
            <input id="questionarie" type="text"
                   name="questionarie"
                   style=" margin:0 10px;width: 30px"/>
        </fieldset>
        <fieldset class="float-left"  style="height: 32px;">
            <input type="checkbox" name="new_date" title="Назначить другую дату"/>
            <label for="new_date_value" style="font-weight: bold;">Дата:</label>
            <?php echo JHtml::calendar($this->date, 'new_date_value', 'new_date_value', '%Y-%m-%d', 'readonly="readonly" onchange="javascript: document.forms.list.new_date.checked = true;"');?>
        </fieldset>
        <button class="float-left" style="margin: 30px 0 0 30px">Назначить</button>
    </div>
    <table class="zebra">
        <thead>
        <tr>
    	    <th>#</th>
            <th><input type="checkbox" id="checkAllBtn"/></th>
            <th>Дата</th>
            <th>Специальность</th>
            <th>Язык</th>
            <th>Семестр</th>
            <th>ФИО</th>
            <th>EID:TID:STATUS</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=0;?>
        <?php foreach ($this->listStudents as $student): ?>
        <tr>
            <td><?php echo ++$i;?></td>
            <td>
                <input type="checkbox" class="chkS teacher-<?php echo $student->teacher_ref;?>" name="students[<?php echo $student->key;?>]"
                       value="<?php echo $student->date . ' ' . $student->stime . ' ' . $student->semestr;?>">
            </td>
            <td><?php echo $student->date . ' ' . $student->stime;?></td>
            <td><?php echo '[' . $student->spec_code . '] ' . $student->spec_name;?></td>
            <td><?php echo $student->lang_name;?></td>
            <td><?php echo $student->semestr;?></td>
            <td><?php echo $student->description;?></td>
            <td><?php echo !empty($student->tid) ? $student->tid : null?></td>
        </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php if ($this->lib_access->AccessCheck('createtests_list', 'w')): ?>
    <p>
        <button>Назначить</button>
        <?php echo JHtml::_('form.token'); ?>
    </p>
    <?php endif;?>
    <input type="hidden" name="option" value="com_euniversity_examscontrol">
    <input type="hidden" name="task" value="manage.create"/>
    <input type="hidden" name="type" value="<?php echo $this->type;?>"/>
    <input type="hidden" name="half" value="<?php echo $this->half;?>"/>
    <input type="hidden" name="date" value="<?php echo $this->date;?>"/>
    <input type="hidden" name="discipline" value="<?php echo JRequest::getString('discipline');?>"/>
</form>

<script type="application/javascript">
  (function( $ ) {
      $(function() {
          $('#sTeacher').on('change',function(e){
              e.preventDefault();
              var st = $(this).val();
	      if(st==0){
                  $('.chkS').attr('disabled', false).show();
              }else{
                  $('.chkS').attr('disabled', true).hide();
                  $('.chkS.teacher-'+st).show().attr('disabled', false);
              }
          });
      });
  })(jQuery);
</script>