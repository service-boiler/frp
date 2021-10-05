<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use ServiceBoiler\Prf\Site\Models\Ticket;
use ServiceBoiler\Prf\Site\Support\Excel;

class TicketExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;
	
	
	
	
    function build()
    {
        if (is_null($this->repository)) {
            echo "Невозможно создать xls файл, т.к. не указан репозиторий с данными";
            exit();
        }
        $this->_sheet = $this->getActiveSheet();
        foreach (trans('site::ticket.excel') as $cell => $value) {
            $this->_sheet->setCellValue($cell, $value)->getStyle($cell)->getFont($cell)->setBold(true);
        }
    $count = 2;
    $column = 1;
	$second = 0;
    $this->_sheet->getStyle('A:Z')->getAlignment()->setVertical('center');
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(5);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(5);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(11);
                    $this->_sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(11);
                    $this->_sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(20);
        
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(60);
        $this->_sheet->getStyle(trans('site::excel.col.'.$column))->getAlignment()->setVertical('center');
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(60);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(15);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setAutoSize(true);
            $column++;
        $this->_sheet->getColumnDimension(trans('site::excel.col.'.$column))->setWidth(30);
       
        foreach ($this->repository->all() as $key => $ticket) {
            $this->_buildTicket($ticket, $count);
            $count++;
            
        }

    }

    private function _buildTicket(Ticket $ticket, $count)
    {	
        $column=1;
        //$this->_sheet->getRowDimension($count)->setRowHeight(80);
        $this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $count - 1)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('left');
		
        $column++;
		$this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->id);
		
        $column++;        
		$this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($ticket->getAttribute('created_at')))
            ->getStyle(trans('site::excel.col.'.$column) . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
			$this->_sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		
        $column++;	
		$this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($ticket->getAttribute('closed_at')))
            ->getStyle(trans('site::excel.col.'.$column) . $count)
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
			$this->_sheet->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		
		$column++;
        $this->_sheet
			->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->status->name)->getStyle(trans('site::excel.col.'.$column) . $count)
            ->getAlignment()->setHorizontal('center');
		
        $column++;
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->receiver->name)
            ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('left');
		
        $column++;
        if(!empty($ticket->text)){
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->text)
            ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
        $this->_sheet
            ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setWrapText(true);
		}
        
        $column++;
        $message_text='';
        foreach($ticket->messages as $message){
        $message_text=$message_text .$message->text .chr(10);
		}
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $message_text)
            ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
        $this->_sheet
            ->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setWrapText(true);
            
        $column++;
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->consumer_name)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		
        $column++;
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->consumer_company)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		
        $column++;
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->consumer_email)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		
        $column++;
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->consumer_phone)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		
        $column++;
        if(!empty($ticket->locality)){
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->locality)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
        }
        
        $column++;
        if(!empty($ticket->region)){
		$this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->region->id)->getStyle(trans('site::excel.col.'.$column) . $count)->getAlignment()->setHorizontal('center');
		}
        
        $column++;
        $this->_sheet
            ->setCellValue(trans('site::excel.col.'.$column) . $count, $ticket->createdBy->name)->getStyle(trans('site::excel.col.'.$column) . $count)
            ->getAlignment()->setHorizontal('left');
			
    }
}
