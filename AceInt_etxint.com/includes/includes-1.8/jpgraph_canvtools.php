<?php
/*=======================================================================
// File: 	JPGRAPH_CANVTOOLS.PHP
// Description:	Some utilities for text and shape drawing on a canvas
// Created: 	2002-08-23
// Author:	Johan Persson (johanp@aditus.nu)
// Ver:		$Id: jpgraph_canvtools.php,v 1.6 2002/09/17 21:02:51 aditus Exp $
//
// License:	This code is released under QPL
// Copyright (C) 2001,2002 Johan Persson
//========================================================================
*/

DEFINE('CORNER_TOPLEFT',0);
DEFINE('CORNER_TOPRIGHT',1);
DEFINE('CORNER_BOTTOMRIGHT',2);
DEFINE('CORNER_BOTTOMLEFT',3);


//===================================================
// CLASS CanvasScale
// Description: Define a scale for canvas so we
// can abstract away with absolute pixels
//===================================================
 
class CanvasScale {
    var $g;
    var $w,$h;
    var $ixmin=0,$ixmax=10,$iymin=0,$iymax=10;

    function CanvasScale(&$graph) {
	$this->g = &$graph;
	$this->w = $graph->img->width;
	$this->h = $graph->img->height;
    }
    
    function Set($xmin=0,$xmax=10,$ymin=0,$ymax=10) {
	$this->ixmin = $xmin;
	$this->ixmax = $xmax;
	$this->iymin = $ymin;
	$this->iymax = $ymax;
    }

    function Translate($x,$y) {
	$xp = round(($x-$this->ixmin)/($this->ixmax - $this->ixmin) * $this->w);
	$yp = round(($y-$this->iymin)/($this->iymax - $this->iymin) * $this->h);
	return array($xp,$yp);
    }

    function TranslateX($x) {
	$xp = round(($x-$this->ixmin)/($this->ixmax - $this->ixmin) * $this->w);
	return $xp;
    }

    function TranslateY($y) {
	$yp = round(($y-$this->iymin)/($this->iymax - $this->iymin) * $this->h);
	return $yp;
    }

}


//===================================================
// CLASS Shape
// Description: Methods to draw shapes on canvas
//===================================================
class Shape {
    var $img,$scale;

    function Shape(&$aGraph,&$scale) {
	$this->img = &$aGraph->img;
	$this->img->SetColor('black');
	$this->scale = &$scale;
    }

    function SetColor($aColor) {
	$this->img->SetColor($aColor);
    }

    function Line($x1,$y1,$x2,$y2) {
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	list($x2,$y2) = $this->scale->Translate($x2,$y2);
	$this->img->Line($x1,$y1,$x2,$y2);
    }

    function Rectangle($x1,$y1,$x2,$y2) {
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	list($x2,$y2)   = $this->scale->Translate($x2,$y2);
	$this->img->Rectangle($x1,$y1,$x2,$y2);
    }

    function FilledRectangle($x1,$y1,$x2,$y2) {
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	list($x2,$y2)   = $this->scale->Translate($x2,$y2);
	$this->img->FilledRectangle($x1,$y1,$x2,$y2);
    }
    
    function Circle($x1,$y1,$r) {
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	$r   = $this->scale->TranslateX($r);
	$this->img->Circle($x1,$y1,$r);
    }

    function FilledCircle($x1,$y1,$r) {
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	$r   = $this->scale->TranslateX($r);
	$this->img->FilledCircle($x1,$y1,$r);
    }

    function RoundedRectangle($x1,$y1,$x2,$y2,$r=null) {    
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	list($x2,$y2)   = $this->scale->Translate($x2,$y2);

	if( $r == null )
	    $r = 5;
	else 
	    $r = $this->scale->TranslateX($r);
	$this->img->RoundedRectangle($x1,$y1,$x2,$y2,$r);
    }

    function FilledRoundedRectangle($x1,$y1,$x2,$y2,$r=null) {    
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	list($x2,$y2)   = $this->scale->Translate($x2,$y2);

	if( $r == null )
	    $r = 5;
	else 
	    $r = $this->scale->TranslateX($r);
	$this->img->FilledRoundedRectangle($x1,$y1,$x2,$y2,$r);    
    }

    function ShadowRectangle($x1,$y1,$x2,$y2,$fcolor=false,$shadow_width=null,$shadow_color=array(102,102,102)) {
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	list($x2,$y2) = $this->scale->Translate($x2,$y2);
	if( $shadow_width == null ) 
	    $shadow_width=4;
	else
	    $shadow_width=$this->scale->TranslateX($shadow_width);
	$this->img->ShadowRectangle($x1,$y1,$x2,$y2,$fcolor,$shadow_width,$shadow_color);
    }

    function SetTextAlign($halign,$valign="bottom") {
	$this->img->SetTextAlign($halign,$valign="bottom");
    }

    function StrokeText($x1,$y1,$txt,$dir=0,$paragraph_align="left") {
	list($x1,$y1) = $this->scale->Translate($x1,$y1);
	$this->img->StrokeText($x1,$y1,$txt,$dir,$paragraph_align);
    }

    // A rounded rectangle where one of the corner has been moved "into" the
    // rectangle 'iw' width and 'ih' height. Corners:
    // 0=Top left, 1=top right, 2=bottom right, 3=bottom left
    function IndentedRectangle($xt,$yt,$w,$h,$iw=0,$ih=0,$aCorner=3,$aFillColor="",$r=4) {
    
	list($xt,$yt) = $this->scale->Translate($xt,$yt);
	list($w,$h)   = $this->scale->Translate($w,$h);
	list($iw,$ih) = $this->scale->Translate($iw,$ih);
	
	$xr = $xt + $w - 0;
	$yl = $yt + $h - 0;

	switch( $aCorner ) {
	    case 0: // Upper left
	    
		// Bottom line, left &  right arc
		$this->img->Line($xt+$r,$yl,$xr-$r,$yl);
		$this->img->Arc($xt+$r,$yl-$r,$r*2,$r*2,90,180);
		$this->img->Arc($xr-$r,$yl-$r,$r*2,$r*2,0,90);

		// Right line, Top right arc
		$this->img->Line($xr,$yt+$r,$xr,$yl-$r);
		$this->img->Arc($xr-$r,$yt+$r,$r*2,$r*2,270,360);

		// Top line, Top left arc
		$this->img->Line($xt+$iw+$r,$yt,$xr-$r,$yt);
		$this->img->Arc($xt+$iw+$r,$yt+$r,$r*2,$r*2,180,270);

		// Left line
		$this->img->Line($xt,$yt+$ih+$r,$xt,$yl-$r);

		// Indent horizontal, Lower left arc
		$this->img->Line($xt+$r,$yt+$ih,$xt+$iw-$r,$yt+$ih);
		$this->img->Arc($xt+$r,$yt+$ih+$r,$r*2,$r*2,180,270);

		// Indent vertical, Indent arc
		$this->img->Line($xt+$iw,$yt+$r,$xt+$iw,$yt+$ih-$r);
		$this->img->Arc($xt+$iw-$r,$yt+$ih-$r,$r*2,$r*2,0,90);

		if( $aFillColor != '' ) {
		    $bc = $this->img->current_color_name;
		    $this->img->PushColor($aFillColor);
		    $this->img->FillToBorder($xr-$r,$yl-$r,$bc);
		    $this->img->PopColor();
		}

		break;

	    case 1: // Upper right

		// Bottom line, left &  right arc
		$this->img->Line($xt+$r,$yl,$xr-$r,$yl);
		$this->img->Arc($xt+$r,$yl-$r,$r*2,$r*2,90,180);
		$this->img->Arc($xr-$r,$yl-$r,$r*2,$r*2,0,90);

		// Left line, Top left arc
		$this->img->Line($xt,$yt+$r,$xt,$yl-$r);
		$this->img->Arc($xt+$r,$yt+$r,$r*2,$r*2,180,270);

		// Top line, Top right arc
		$this->img->Line($xt+$r,$yt,$xr-$iw-$r,$yt);
		$this->img->Arc($xr-$iw-$r,$yt+$r,$r*2,$r*2,270,360);

		// Right line
		$this->img->Line($xr,$yt+$ih+$r,$xr,$yl-$r);

		// Indent horizontal, Lower right arc
		$this->img->Line($xr-$iw+$r,$yt+$ih,$xr-$r,$yt+$ih);
		$this->img->Arc($xr-$r,$yt+$ih+$r,$r*2,$r*2,270,360);

		// Indent vertical, Indent arc
		$this->img->Line($xr-$iw,$yt+$r,$xr-$iw,$yt+$ih-$r);
		$this->img->Arc($xr-$iw+$r,$yt+$ih-$r,$r*2,$r*2,90,180);

		if( $aFillColor != '' ) {
		    $bc = $this->img->current_color_name;
		    $this->img->PushColor($aFillColor);
		    $this->img->FillToBorder($xt+$r,$yl-$r,$bc);
		    $this->img->PopColor();
		}

		break;

	    case 2: // Lower right
		// Top line, Top left & Top right arc
		$this->img->Line($xt+$r,$yt,$xr-$r,$yt);
		$this->img->Arc($xt+$r,$yt+$r,$r*2,$r*2,180,270);
		$this->img->Arc($xr-$r,$yt+$r,$r*2,$r*2,270,360);

		// Left line, Bottom left arc
		$this->img->Line($xt,$yt+$r,$xt,$yl-$r);
		$this->img->Arc($xt+$r,$yl-$r,$r*2,$r*2,90,180);

		// Bottom line, Bottom right arc
		$this->img->Line($xt+$r,$yl,$xr-$iw-$r,$yl);
		$this->img->Arc($xr-$iw-$r,$yl-$r,$r*2,$r*2,0,90);

		// Right line
		$this->img->Line($xr,$yt+$r,$xr,$yl-$ih-$r);
	    
		// Indent horizontal, Lower right arc
		$this->img->Line($xr-$r,$yl-$ih,$xr-$iw+$r,$yl-$ih);
		$this->img->Arc($xr-$r,$yl-$ih-$r,$r*2,$r*2,0,90);

		// Indent vertical, Indent arc
		$this->img->Line($xr-$iw,$yl-$r,$xr-$iw,$yl-$ih+$r);
		$this->img->Arc($xr-$iw+$r,$yl-$ih+$r,$r*2,$r*2,180,270);

		if( $aFillColor != '' ) {
		    $bc = $this->img->current_color_name;
		    $this->img->PushColor($aFillColor);
		    $this->img->FillToBorder($xt+$r,$yt+$r,$bc);
		    $this->img->PopColor();
		}

		break;

	    case 3: // Lower left
		// Top line, Top left & Top right arc
		$this->img->Line($xt+$r,$yt,$xr-$r,$yt);
		$this->img->Arc($xt+$r,$yt+$r,$r*2,$r*2,180,270);
		$this->img->Arc($xr-$r,$yt+$r,$r*2,$r*2,270,360);

		// Right line, Bottom right arc
		$this->img->Line($xr,$yt+$r,$xr,$yl-$r);
		$this->img->Arc($xr-$r,$yl-$r,$r*2,$r*2,0,90);

		// Bottom line, Bottom left arc
		$this->img->Line($xt+$iw+$r,$yl,$xr-$r,$yl);
		$this->img->Arc($xt+$iw+$r,$yl-$r,$r*2,$r*2,90,180);

		// Left line
		$this->img->Line($xt,$yt+$r,$xt,$yl-$ih-$r);
	    
		// Indent horizontal, Lower left arc
		$this->img->Line($xt+$r,$yl-$ih,$xt+$iw-$r,$yl-$ih);
		$this->img->Arc($xt+$r,$yl-$ih-$r,$r*2,$r*2,90,180);

		// Indent vertical, Indent arc
		$this->img->Line($xt+$iw,$yl-$ih+$r,$xt+$iw,$yl-$r);
		$this->img->Arc($xt+$iw-$r,$yl-$ih+$r,$r*2,$r*2,270,360);

		if( $aFillColor != '' ) {
		    $bc = $this->img->current_color_name;
		    $this->img->PushColor($aFillColor);
		    $this->img->FillToBorder($xr-$r,$yt+$r,$bc);
		    $this->img->PopColor();
		}

		break;
	}
    }
}


//===================================================
// CLASS RectangleText
// Description: Draws a text paragraph inside a 
// rounded, possible filled, rectangle.
//===================================================
class CanvasRectangleText {
    var $ix,$iy,$iw,$ih,$ir=4;
    var $iTxt,$iColor='black',$iFillColor='',$iFontColor='black';
    var $iParaAlign='center';
    var $iAutoBoxMargin=5;
    var $iShadowWidth=3,$iShadowColor='';

    function CanvasRectangleText($aTxt='',$xl=0,$yt=0,$w=0,$h=0) {
	$this->iTxt = new Text($aTxt);
	$this->ix = $xl;
	$this->iy = $yt;
	$this->iw = $w;
	$this->ih = $h;
    }
 
    function SetShadow($aColor='gray',$aWidth=3) {
	$this->iShadowColor = $aColor;
	$this->iShadowWidth = $aWidth;
    }

    function SetFont($FontFam,$aFontStyle,$aFontSize=12) {
	$this->iTxt->SetFont($FontFam,$aFontStyle,$aFontSize);
    }

    function SetTxt($aTxt) {
	$this->iTxt->Set($aTxt);
    }

    function ParagraphAlign($aParaAlign) {
	$this->iParaAlign = $aParaAlign;
    }

    function SetFillColor($aFillColor) {
	$this->iFillColor = $aFillColor;
    }

    function SetAutoMargin($aMargin) {
	$this->iAutoBoxMargin=$aMargin;
    }

    function SetColor($aColor) {
	$this->iColor = $aColor;
    }

    function SetFontColor($aColor) {
	$this->iFontColor = $aColor;
    }

    function SetPos($xl=0,$yt=0,$w=0,$h=0) {
	$this->ix = $xl;
	$this->iy = $yt;
	$this->iw = $w;
	$this->ih = $h;
    }

    function Pos($xl=0,$yt=0,$w=0,$h=0) {
	$this->ix = $xl;
	$this->iy = $yt;
	$this->iw = $w;
	$this->ih = $h;
    }

    function Set($aTxt,$xl,$yt,$w=0,$h=0) {
	$this->iTxt->Set($aTxt);
	$this->ix = $xl;
	$this->iy = $yt;
	$this->iw = $w;
	$this->ih = $h;
    }

    function SetCornerRadius($aRad=5) {
	$this->ir = $aRad;
    }

    function Stroke($aImg,$scale) {
	
	// If coordinates are specifed as negative this means we should
	// treat them as abolsute (pixels) coordinates
	if( $this->ix > 0 ) {
	    $this->ix = $scale->TranslateX($this->ix) ;
	}
	else {
	    $this->ix = -$this->ix;
	}

	if( $this->iy > 0 ) {
	    $this->iy = $scale->TranslateY($this->iy) ;
	}
	else {
	    $this->iy = -$this->iy;
	}
	    
	list($this->iw,$this->ih) = $scale->Translate($this->iw,$this->ih) ;

	if( $this->iw == 0 ) 
	    $this->iw = round($this->iTxt->GetWidth($aImg) + $this->iAutoBoxMargin);
	if( $this->ih == 0 ) {
	    $this->ih = round($this->iTxt->GetTextHeight($aImg) + $this->iAutoBoxMargin);
	}

	if( $this->iShadowColor != '' ) {
	    $aImg->PushColor($this->iShadowColor);
	    $aImg->FilledRoundedRectangle($this->ix+$this->iShadowWidth,
					  $this->iy+$this->iShadowWidth,
					  $this->ix+$this->iw-1+$this->iShadowWidth,
					  $this->iy+$this->ih-1+$this->iShadowWidth,
					  $this->ir);
	    $aImg->PopColor();	    
	}

	if( $this->iFillColor != '' ) {
	    $aImg->PushColor($this->iFillColor);
	    $aImg->FilledRoundedRectangle($this->ix,$this->iy,
					  $this->ix+$this->iw-1,
					  $this->iy+$this->ih-1,
					  $this->ir);
	    $aImg->PopColor();
	}

	if( $this->iColor != '' ) {
	    $aImg->PushColor($this->iColor);
	    $aImg->RoundedRectangle($this->ix,$this->iy,
				    $this->ix+$this->iw-1,
				    $this->iy+$this->ih-1,
				    $this->ir);
	    $aImg->PopColor();
	}
	
	$this->iTxt->Align('center','center');
	$this->iTxt->ParagraphAlign($this->iParaAlign);
	$this->iTxt->SetColor($this->iFontColor);
	$this->iTxt->Stroke($aImg, $this->ix+$this->iw/2, $this->iy+$this->ih/2);

	return array($this->iw, $this->ih);

    }

}


?>