<?php
class AppPDF{
	public static function StandardPDF($content){
		/* 
		** PDF Time
		*/
	    $generateddate = date('d-m-Y');
	    $pdf_header = "<h3>Compliance Report</h3>";
	    $pdf_header.= "<p>Generated on $generateddate</p>";
	    $pdf_header.="<p><strong>Kindly Confirm compliance with the RF QC Team</strong></p>";
	    

	    $pdf_file = $pdf_header.$content;
		$pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());

		$filename = "Compliance_Report_".date('dmYhis');
		$filename = str_replace(" ","_",$filename);
		$filename = preg_replace('/[^\w\d_ -]/si', '', $filename);
		$filename_pdf = $filename.'.pdf';

		$location = $_SERVER['DOCUMENT_ROOT']."/rflogs/docs/pdf/".$filename_pdf;

		if(file_put_contents($location, $pdf)){
			$file = Yii::app()->request->baseUrl . '/docs/pdf/'.$filename_pdf;
		    $fppackage = "<a href='$file' class='btn btn-danger btn-sm' target='_blank'><i class='fa fa-file-pdf-o'></i> Download PDF</a>";
		}else{
		    $fppackage = "";
		}
		return $fppackage;
	}
}
