<?php
require 'lib/TCPDF/CustomPdfGenerator.php';
class NoteController extends Controller {

    public function list_note()
    {
        $note = new Note($this->db);
        $person_num = $this->f3->get('PARAMS.PersonNum');
        $notes = $note->getByPersonNumJoin($person_num);
        $this->f3->set('notes', $notes);
        $this->f3->set('page_head','Notas');
        $this->f3->set('view','note/list_note.htm');
    }
// ***************************************************************************************************

    public function calcula_edad($fecha){
        $fechnaci = explode('/', $fecha);
        $dianaci   = intval($fechnaci[0]);
        $mesnaci = intval($fechnaci[1]);
        $anonaci  = intval($fechnaci[2]);
        $fechoy = explode('/', date('d/m/Y'));
        $diahoy   = intval($fechoy[0]);
        $meshoy = intval($fechoy[1]);
        $anohoy  = intval($fechoy[2]);  
        $anos = $anohoy - $anonaci;
        if ($meshoy > $mesnaci) {
           $meses = $meshoy - $mesnaci;
        }else{
            $meses = (12-$mesnaci) + $meshoy;
            $anos = $anos - 1;
        }
        if ($diahoy > $dianaci) {
            $dias = $diahoy - $dianaci;
        }else{
            if ($meshoy == $mesnaci){
                $meses = 11;
            }else{
                $meses = $meses - 1;
            }
            $dias = (30-$dianaci) + $diahoy;
        }
        return $anos.' anos, '.$meses.' meses y '.$dias.' dias.';
    }

    public function delete()
    {
        if($this->f3->exists('PARAMS.id'))
        {
            $user = new User($this->db);
            $user->delete($this->f3->get('PARAMS.id'));
        }
        $this->f3->reroute('/success/Consulta Eliminada');
    }

public function updateStatus()
{
    $note_num = $this->f3->get('POST.note_num');
    $new_status = $this->f3->get('POST.status');
	$data = array();
    if ($note_num === null) {
        die(json_encode(['success' => false, 'message' => 'Invalid note Num']));
        return;
    }

    $note = new Note($this->db);
    $result = $note->updateNoteStatus($note_num, $new_status);

    if ($result) {
        $data['success'] = true;
		die(json_encode($data));  
        //die(json_encode(['success' => true]));
    } else {
        $data['success'] = false;		 
		$data['message'] = 'Failed to update note';
		die(json_encode($data));         
        //die(json_encode(['success' => false, 'message' => 'Failed to update note']));
    }
}


	// Method to generate PDF report for a selected paciente
	public function generar_pdf()
	{

		// Get the paciente data from the database
		$paciente = new Paciente($this->db);
        $consulta = new Consulta($this->db);
        $cita = new Cita($this->db);

        $paciente->getById($this->f3->get('PARAMS.CodHistoria'));

        $numcita = $this->f3->get('PARAMS.NumCita');

       // die('entro');
        $consulta->getByNumCita($numcita);

        $cita->getFechCita($numcita);
       
		$pdf = new CustomPdfGenerator();

		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('helvetica', 'B', 14);

		// start a new page
		$pdf->AddPage();

		$pdf->writeHTML("<b>INFORME MEDICO</b>", true, false, false, false, 'C');
		$pdf->SetX(130);


		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,20,'Fecha Consulta:',0,0,'R');
		// el valor antes del centrado corresponde a si se dejara linea a continuacion o no: 0=no linea   1= linea a continuacion
		$pdf->SetFont('dejavusans', '', 12, '', true);
		$pdf->Cell(25,20,$cita->FechCita,0,1,'R');
	
		//PACIENTE    HISTORIA
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'Paciente:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(34);
		$pdf->Cell(1,10,'  ' . $paciente->NomPaci . '  ' . $paciente->ApePaci,0,0,'L');

		$pdf->SetX(140);
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'Historia:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(160);
		$pdf->Cell(1,10,'  ' .$paciente->CodHistoria,0,1,'L');

		//MOTIVO DE CONSULTA
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'Motivo de Consulta:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(58);
		$pdf->Cell(1,10,'  ' .$consulta->MotivoConsulta,0,1,'L');

		//Exploracion y Evaluacion Clinica
		$pdf->SetFont('helvetica', 'B', 14);
		$pdf->writeHTML("<b>Exploracion y Evaluacion Clinica</b>", true, false, false, false, 'C');
		$pdf->SetFont('dejavusans', '', 12, '', true);

		// AVOD
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'A.V.O.D  S/C:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(43);
		$pdf->Cell(1,10,'  ' . $consulta->AVOD20,0,0,'L');

		$pdf->SetX(64);
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'CC:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(71);
		$pdf->Cell(1,10,'  ' .$consulta->AVODCC,0,0,'L');

		$pdf->SetX(92);
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'J:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(95);
		$pdf->Cell(1,10,'  ' .$consulta->AVODJ,0,1,'L');

		// AVOI
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'A.V.O.I  S/C:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(43);
		$pdf->Cell(1,10,'  ' . $consulta->AVOI20,0,0,'L');

		$pdf->SetX(64);
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'CC:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(71);
		$pdf->Cell(1,10,'  ' .$consulta->AVOICC,0,0,'L');

		$pdf->SetX(92);
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'J:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(95);
		$pdf->Cell(1,10,'  ' .$consulta->AVOIJ,0,1,'L');

		// Keratometria
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'Keratometria     OD:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(57);
		$pdf->Cell(1,10,'  ' . $consulta->KTOD,0,0,'L');

		$pdf->SetX(78);
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'OI:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(84);
		$pdf->Cell(1,10,'  ' .$consulta->KTOI,0,1,'L');

		// Tonometria
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'Tonometria       OD:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(57);
		$pdf->Cell(1,10,'  ' . $consulta->TONOD,0,0,'L');

		$pdf->SetX(78);
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(1,10,'OI:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(84);
		$pdf->Cell(1,10,'  ' .$consulta->TONOI,0,1,'L');

		$pdf->Write(0, "\n", '', 0, 'C', true, 0, false, false, 0);
		// Refraccion
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(0,0,'Refraccion:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(41);
		$pdf->MultiCell(0, 0, '  ' .$consulta->Refraccion, 0, 'L', false, 1);
		
		$pdf->Write(0, "\n\n", '', 0, 'C', true, 0, false, false, 0);
		// Refraccion
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(0,0,'Gonoscopia:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(43);
		$pdf->MultiCell(0, 0, '  ' .$consulta->Gonoscopia, 0, 'L', false, 1);
		
		$pdf->Write(0, "\n\n", '', 0, 'C', true, 0, false, false, 0);
		// Vision Cromatica
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(0,0,'Vision Cromatica:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(54);
		$pdf->MultiCell(0, 0, '  ' .$consulta->VisionCromatica, 0, 'L', false, 1);
		
		$pdf->Write(0, "\n\n", '', 0, 'C', true, 0, false, false, 0);		
		// Motor Ocular
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(0,0,'Motor Ocular:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(46);
		$pdf->MultiCell(0, 0, '  ' .$consulta->MotorOcular, 0, 'L', false, 1);
		
		$pdf->Write(0, "\n\n", '', 0, 'C', true, 0, false, false, 0);		
		// Biomicroscopia
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(0,0,'Biomicroscopia:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(52);
		$pdf->MultiCell(0, 0, '  ' .$consulta->Biomicroscopia, 0, 'L', false, 1);
		
		$pdf->Write(0, "\n\n", '', 0, 'C', true, 0, false, false, 0);
		// Fondoscopia
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(0,0,'Fondoscopia:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(46);
		$pdf->MultiCell(0, 0, '  ' .$consulta->Fondoscopia, 0, 'L', false, 1);
		
		$pdf->Write(0, "\n\n", '', 0, 'C', true, 0, false, false, 0);
		// Diagnostico
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(0,0,'Diagnostico:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(44);
		$pdf->MultiCell(0, 0, '  ' .$consulta->Diagnostico, 0, 'L', false, 1);

		$pdf->Write(0, "\n\n", '', 0, 'C', true, 0, false, false, 0);
		// TratamientoObservaciones
		$pdf->SetFont('helvetica', 'B', 13);
		$pdf->Cell(0,0,'Observaciones:',0,0,'L');
		$pdf->SetFont('dejavusans', '', 11, '', true);
		$pdf->SetX(50);
		$pdf->MultiCell(0, 0, '  ' .$consulta->TratamientoObservaciones, 0, 'L', false, 1);

		

		$pdf->Ln();
		$pdf->Output('HistoriaPaciente.pdf', 'I'); 
		exit();
	}

}