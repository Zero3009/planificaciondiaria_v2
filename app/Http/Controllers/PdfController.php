<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App;
use View;
use PDF;
use Response;

class PdfController extends Controller
{
    public function invoice(Request $request) 
    {

        $coleccion = $request->coleccion;
        $date = date('Y-m-d');

        return View::make('pdf.invoice', compact('coleccion', 'date'))->render();

        //return View::make('pdf.invoice', compact('data', 'date', 'invoice'))->render();*/
        //$pdf = PDF::loadView('pdf.invoice',compact('data','date','invoice'));
        //return $pdf->stream();


        /*$pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->download('invoice.pdf');*/
    }
 
    public function getData() 
    {
        $data =  [
            'quantity'      => '1' ,
            'description'   => 'some ramdom text',
            'price'   => '500',
            'total'     => '500'
        ];
        return $data;
    }
}
