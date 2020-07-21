<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('tcpdf.tcpdf');
jimport('euniversity.php.managelib.managelib');
jimport('euniversity.php.access.access');

//echo "<hr><h4>_POST</h4>";
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/

class MYPDF extends TCPDF
{
	public function Header()
	{
		$ticket = $this->myvar['ticket'];

		$half = $this->myvar['half'];
		$semestr = $this->myvar['semestr'];
		$protocol_id = $this->myvar['protocol_id'];
		$protocol_date = $this->myvar['protocol_date'];
		$date = $this->myvar['date'];
		$year = (string)$this->myvar['year'];
		$discipline = "[".$this->myvar['discipline_code']."] ".$this->myvar['discipline_name'];
		$spec = $this->myvar['spec'];
		$teacher = $this->myvar['teacher'];
		$lang = $this->myvar['lang_name'];
		$quests = $this->myvar['quests'];

		$imageStamp = $this->myvar['imageStamp'];
		
		$imageScissors = $this->myvar['imageScissors'];

		$this->SetFont('freesans', '', 8, '', true);

		$this->setAlpha(0.75);
		$this->Image($imageStamp, 178, 8, 22);
		$this->Image($imageStamp, 171, 52, 30);
		$this->Image($imageScissors, 4, 27.4, 10);

		$html = '
<table width="100%" border="0">
    <tr>
	<td colspan="5"> </td>
    </tr>
    <tr>
	<td width="12%" align="right"><b>ФИО Студента:</b></td>
	<td width="50%" style="border-bottom: 1px solid #000000;"></td>
	<td width="6%"> </td>
	<td width="12%" align="right"><b>ID Студента:</b></td>
	<td width="20%" style="border-bottom: 1px solid #000000;"></td>
    </tr>
    <tr>
	<td colspan="5"><h1 align="center">[20'.$year[0].$year[1].'-20'.$year[2].$year[3].'] '.$half.'. Вопросник №'.$ticket.'</h1></td>
    </tr>
    <tr>
	<td colspan="5">
	    <table width="100%" border="0">
		<tr>
		    <td width="65%"><b>Дисциплина:</b> <i>'.mb_substr($discipline, 0, 70, 'utf-8').((mb_strlen($discipline, 'utf-8')>70)?'...':'').'</i></td>
		    <td width="35%"><b>Семестр:</b> <i>'.$semestr.'</i></td>
		</tr>
		<tr>
		    <td><b>Специальность:</b> <i>'.mb_substr($spec, 0, 65, 'utf-8').((mb_strlen($spec, 'utf-8')>65)?'...':'').'</i></td>
		    <td><b>Форма обучения:</b> <i>Очная</i></td>
		</tr>
		<tr>
		    <td><b>Отделение:</b> <i>'.$lang.'</i></td>
		    <td><b>Протокол:</b> <i>№'.$protocol_id.' от '.$protocol_date.'</i></td>
		</tr>
		<tr>
		    <td><b>Преподаватель:</b> <i>'.$teacher.'</i></td>
		    <td><b>Дата проведения:</b> <i>'.$date.'</i></td>
		</tr>
	    </table>
	</td>
    </tr>
    <tr>
	<td colspan="5" style="border-bottom: 1px dashed #999999;"> </td>
    </tr>
    <tr>
	<td colspan="5"> </td>
    </tr>
    <tr>
	<td colspan="3"><h1 align="left">Шифр билета: ___ ___ ___ ___ &mdash; ___ ___ ___ ___</h1></td>
	<td colspan="2"><h1 align="right">Вопросник: <u>'.$ticket.'</u></h1></td>
    </tr>
    <tr>
	<td colspan="5"> </td>
    </tr>
    <tr>
	<td colspan="5">
	    <table width="100%" border="0">
		<tr>
		    <td width="15%" align="center"><b>Проверяющий <br/>Преподаватель:</b></td>
		    <td width="27%" style="border-bottom: 1px solid #000000;"><br/><br/><i>'.$teacher.'</i></td>
		    <td width="2%"></td>
		    <td width="15%" align="center"><b>Оценка <br/>преподавателя:</b></td>
		    <td width="12%" style="border-bottom: 1px solid #000000;"></td>
		    <td width="2%"></td>
		    <td width="15%" align="center"><b>Подпись <br/>преподавателя:</b></td>
		    <td width="12%" style="border-bottom: 1px solid #000000;"></td>
		</tr>
	    </table>
	</td>
    </tr>
    <tr>
	<td colspan="5" width="85%">
	    <ol>';

		foreach ($quests as $quest)
		{
		    $html .= '<li><p style="font-family: arialuni">'.$quest.'</p></li>';
		}

		$html .= '
	    </ol>
	</td>
    </tr>
</table>
';
		$this->setAlpha(1);
		$this->writeHTML($html, true, 0);

	}
}

class eUniversityExamsControlViewCreateTickets extends JView
{
	function getImage($url)
	{
	
            $domain = JURI::root();
            $image_file = "$domain/$url";
                $cs = curl_init(); // curl session
                curl_setopt($cs, CURLOPT_URL, $image_file);
                curl_setopt($cs, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($cs, CURLOPT_FAILONERROR, true);
                curl_setopt($cs, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cs, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($cs, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($cs, CURLOPT_TIMEOUT, 30);
                curl_setopt($cs, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($cs, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($cs, CURLOPT_USERAGENT, 'TCPDF');
                $imgdata = curl_exec($cs);
				
				//var_dump(curl_exec($cs));
				//var_dump(curl_getinfo($cs));
				//var_dump(curl_getinfo($cs));
				
                curl_close($cs);
               
		
		return '@'.base64_encode($imgdata);
	}

	function display($tpl = null)
	{
		
		$ticket=JRequest::getInt('test_id');
		$model = $this->getModel();
		$ar = $model->updateTicket($ticket);
		$arrTickets = $model->generateTickets($ar->test_id, $ar->count, $ar->semestr, $ar->streams);

#		echo "<pre>";
#		print_r($arrTickets);
#		echo "</pre>";
#		exit;

		$document =& JFactory::getDocument();
		$document->setMimeEncoding('application/pdf');
		JResponse::setHeader('Content-disposition', 'attachment; filename="Шифрованные_билеты_#' . vsprintf('%08s', array($ar->test_id)) . '.pdf"; creation-date="' . JFactory::getDate()->toRFC822() . '"', true);

		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->setPrintFooter(false);
		
		$pdf->myvar['imageStamp'] = $this->getImage("index.php?option=com_euniversity_journal2&view=e_print&format=raw&num=". base64_encode($ar->test_id));				
		$pdf->myvar['imageScissors'] = $this->getImage("components/com_euniversity_examscontrol/assets/img/ng3.png");

		$pdf->myvar['ticket'] = $ar->test_id;
		$pdf->myvar['discipline_code'] = $ar->discipline->code;
		$pdf->myvar['discipline_name'] = $ar->discipline->description;
		$pdf->myvar['lang_name'] = $ar->lang->description;
		$pdf->myvar['year'] = $ar->year;
		$pdf->myvar['semestr'] = $ar->semestr;
		$pdf->myvar['date'] = $ar->date;
                $pdf->myvar['protocol_id'] = $ar->protocol_id;
                $pdf->myvar['protocol_date'] = $ar->protocol_date;
		$pdf->myvar['half'] = (($ar->semestr%2)?'Зимняя сессия':'Летняя сессия');

		$q = 0;
		
	//echo $ar->discipline->ref;
	//exit;
		foreach ($ar->streams as $stream)
		{
		    for ($i=1; $i<=$stream->students_count; $i++)
		    {
            		$pdf->myvar['spec'] = $stream->spec_name;
            		$pdf->myvar['teacher'] = $stream->teacher_name;
			if($ar->semestr < 3)
			{
				list($scep_ref,$musor) = explode("--", key($ar->streams));
				if(      $scep_ref == '3e97c199-e343-11e9-80da-025400b39f57'      //Международное право (уск.)
				OR 	$scep_ref == '3e97c197-e343-11e9-80da-025400b39f57'		//Маркетинг и бизнес - коммуникации (уск.)
				OR 	$scep_ref == '3e97c195-e343-11e9-80da-025400b39f57'		//Государственное управление и менеджмент (уск,)
				OR 	$scep_ref == '68de1687-d8ca-11e9-80d7-025400b39f57'		//Международное право (уск.)	
				OR 	$scep_ref == '433a9d19-c525-11e9-80d2-025400b39f57'		//Международные отношения (уск.)
				OR 	$scep_ref == '1bdd15ff-a383-11e9-80ce-025400b39f57'		//Юриспруденция (уск.)	
				OR 	$scep_ref == '1bdd15fd-a383-11e9-80ce-025400b39f57'		//Экономика (уск.)	
				OR 	$scep_ref == '1bdd15fa-a383-11e9-80ce-025400b39f57'		//Учитель двух иностранных языков (уск.)
				OR 	$scep_ref == '4b40ec13-b277-11e9-80d1-025400b39f57'		//Туризм (уск.)
				OR 	$scep_ref == '4b40ec11-b277-11e9-80d1-025400b39f57'		//Ресторанное дело и гостиничный бизнес (уск.)
				OR 	$scep_ref == '4b40ebf0-b277-11e9-80d1-025400b39f57'		//Переводческое дело (китайский язык уск.)
				OR 	$scep_ref == '1bdd1601-a383-11e9-80ce-025400b39f57'		//Иностранная филология уск.
					
				)
					{
						if(
						 $ar->discipline->ref == 'db6ded80-2723-11e3-9294-001fc6e2768c' //Основы туризмологии OT 1203

						OR $ar->discipline->ref == 'a6729239-a4c7-11e7-80d0-002590ea6fbf' //Инфраструктура туризма IT 1202
						
						// OR $ar->discipline->ref == 'a08ad14f-93db-11e8-80e6-002590ea6fbf' //История туризма IstT 1204
						
						OR $ar->discipline->ref == 'a08ad176-93db-11e8-80e6-002590ea6fbf' //Технология обслуживания в ресторанах и гостиницах TORG 1207
						
						OR $ar->discipline->ref == 'fce044e0-2b2e-11e7-80c8-002590ea6fbf' //Основы индустрии гостеприимства OIG 1204
				
						OR $ar->discipline->ref == '500b4c12-24ee-11e7-80c8-002590ea6fbf' //История государства и права Республики Казахстан и зарубежных стран IGPRKZS 1211
						
						OR $ar->discipline->ref == '8a468405-0dd5-11e2-a245-001fc6e2768c' //Теория государства и права TGP 1203
					
						OR $ar->discipline->ref == '526e9d82-6765-11e2-97ac-001fc6e2768c' //Сравнительное правоведение SP 1202
					
						OR $ar->discipline->ref == 'b9eee3df-c6cf-11df-baf9-001fc6e2768c' //Введение в специальность
					
					
						)
						{
							$pdf->myvar['quests'] = array();
							$pdf->myvar['quests'][] = $arrTickets[1][$q]->question;	
							$pdf->myvar['quests'][] = $arrTickets[2][$q]->question;	
							//$pdf->myvar['quests'][] = $arrTickets[3][$q]->question;
						}else
						{
							$pdf->myvar['quests'] = array();
							$pdf->myvar['quests'][] = $arrTickets[1][$q]->question;	
							$pdf->myvar['quests'][] = $arrTickets[2][$q]->question;	
							$pdf->myvar['quests'][] = $arrTickets[3][$q]->question;
						}
						
					}else
					{
						$pdf->myvar['quests'] = array();
						$pdf->myvar['quests'][] = $arrTickets[1][$q]->question;	
						$pdf->myvar['quests'][] = $arrTickets[2][$q]->question;	
						//$pdf->myvar['quests'][] = $arrTickets[3][$q]->question;
					}
					
			}else
			{
				$pdf->myvar['quests'] = array();
				$pdf->myvar['quests'][] = $arrTickets[1][$q]->question;	
				$pdf->myvar['quests'][] = $arrTickets[2][$q]->question;	
				$pdf->myvar['quests'][] = $arrTickets[3][$q]->question;	
			}
			

			$pdf->AddPage();
			$q++;
		    }
		}
		
		$pdf->Output('vedomost.pdf', 'I');
	}
}