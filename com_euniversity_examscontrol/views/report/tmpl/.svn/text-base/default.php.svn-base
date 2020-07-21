<?php defined('_JEXEC') or die; ?>
<?php $style = '#main,#maininner{width: 100% !important;}';?>
<?php $doc =& JFactory::getDocument();?>
<?php $doc->addStyleDeclaration( $style );?>
<?php JHTML::script('report.js', 'components/com_euniversity_examscontrol/assets/js/'); ?>
<?php $tpl = JRequest::getVar('layout');?>
<?php $i = 1; ?>
<form class="box" name="menu" action="index.php?option=com_euniversity_examscontrol&view=report" method="post">
    <input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout');?>" />
    <h3 class="module-title">Отчеты по контролю знаний</h3>
    <h4 class="module-title">Список отчетов</h4>
    <table class="zebra">
        <thead>
        <tr>
            <th style="width: 30px;">№</th>
            <th>Название</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->reports as $report): ?>
        <tr>
            <td><?php echo $i++;?></td>
            <td>
                <a href="#" class="selectReport" name="<?php echo substr($report,8,-4);?>"><?php echo $report;?></a>
            </td>
        </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    <div>
        <?php if(!empty($tpl)): echo $this->loadTemplate($tpl);endif;?>
    </div>
</form>

