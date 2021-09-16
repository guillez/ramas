<?php
/**
 * Created by PhpStorm.
 * User: ngazcon
 * Date: 05/10/18
 * Time: 15:46
 */

require_once(toba::proyecto()->get_path().'/php/3ros/tcpdf/tcpdf.php');


class custom_tcpdf extends  tcpdf
{
    public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }

    public function Header() {
        if ($this->page == 1) {
            // Logo
            $image_file_default = toba::proyecto()->get_path() . '/www/img/logo_univ.jpg';
            $image_file = toba::proyecto()->get_path() . '/www/img/custom_pdf_image.png';
            if (file_exists($image_file)) {
                $this->Image($image_file, 10, 7, '50', '20', 'PNG', '', 'T', false, 300, '', false, false, 0, true, false, false);
            } else {
                $this->Image($image_file_default, 10, 7, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }

            // Set font
            $this->SetFont('courier', 'I', 12);
            // Title
            $nombre_institucion = $inst = toba::consulta_php('consultas_mgi')->get_institucion();
            if (!empty($nombre_institucion)) {
                $nombre = $nombre_institucion[0]['nombre'];
            } else {
                $nombre = '';
            }

            $this->SetXY(10, 12);
            $this->Cell(0, 0, $nombre, 0, false, 'C', 0, '', 0, false, 'T', 'T');
        }
    }

    public function Footer() {
        // Lo vuelo para que no imprima la línea negra
        $w_page = isset($this->l['w_page']) ? $this->l['w_page'].' ' : '';
        if (empty($this->pagegroups)) {
            $pagenumtxt = $w_page.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
        } else {
            $pagenumtxt = $w_page.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
        }
        $this->SetX($this->original_lMargin);
        //$this->Cell(0, 0, $this->getAliasRightShift().$pagenumtxt, 'T', 0, 'R');
        $this->Cell(0, 0, $this->getAliasRightShift().$pagenumtxt, '', 0, 'R');
    }

}