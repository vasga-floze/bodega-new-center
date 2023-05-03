<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    protected $col = 0;
	//MultiCell with bullet
    function SetCol($col)
    {
        // Move position to a column
        $this->col = $col;
        $x = 10 + $col*65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    function AcceptPageBreak()
    {
        if($this->col<2)
        {
            // Go to next column
            $this->SetCol($this->col+1);
            $this->SetY(10);
            return false;
        }
        else
        {
            // Go back to first column and issue page break
            $this->SetCol(0);
            return true;
        }
    }
	function MultiCellBlt($w, $h, $blt, $txt, $border=0, $align='J', $fill=false)
	{
		//Get bullet width including margins
		$blt_width = $this->GetStringWidth($blt)+$this->cMargin*2;

		//Save x
		$bak_x = $this->x;

		//Output bullet
		$this->Cell($blt_width,$h,$blt,0,'',$fill);

		//Output text
		$this->MultiCell($w-$blt_width,$h,$txt,$border,$align,$fill);

		//Restore x
		$this->x = $bak_x;
	}
}
?>