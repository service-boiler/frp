<?php

namespace ServiceBoiler\Prf\Site\Exports\Excel;

use ServiceBoiler\Prf\Site\Models\Message;
use ServiceBoiler\Prf\Site\Models\Order;
use ServiceBoiler\Prf\Site\Models\OrderItem;
use ServiceBoiler\Prf\Site\Support\Excel;
use Site;

class OrderExcel extends Excel
{
    /** @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet Sheet */
    private $_sheet;


	/**
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	function build()
    {
        if (is_null($this->model)) {
            echo "Невозможно создать xls файл, т.к. не указана модель с данными";
            exit();
        }
        /** @var Order $order */
        $order = $this->model;
        $this->getProperties()->setTitle(trans('site::order.distributor'));


        $this->_sheet = $this->getActiveSheet();
        $this->_sheet->setTitle(trans('site::order.distributor'));
        $this->_sheet->getStyle('B8')->getAlignment()->setWrapText(true);

        foreach (trans('site::order.excel') as $cell => $value) {
            $this->_sheet->setCellValue($cell, $value);
        }

        $this->_sheet
            ->setCellValue('B2', \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($order->getAttribute('created_at')))
            ->getStyle('B2')
            ->getNumberFormat()
            ->setFormatCode('dd.mm.yyyy');
        $this->_sheet
            ->setCellValue('B1', $order->getAttribute('id'))
            ->setCellValue('B3', $order->contragent->name)
	    ->setCellValue('C3', $order->contragent->inn)
            ->setCellValue('B4', $order->user->name)
            ->setCellValue('B5', $order->user->email)
            ->setCellValue('B6', $order->contacts_comment)
            ->setCellValue('B7', $order->address->name)
            ->setCellValue('B8', $order->messages->implode('text', "\n\n"));
        /** @var Message $message */


        $count = 11;
        /**
         * @var int $key
         * @var OrderItem $item
         */
        foreach ($order->items as $key => $item) {

            $this->_buildOrder($item, $count);

        }

        $this->_sheet->getColumnDimension('A')->setAutoSize(true);
        $this->_sheet->getColumnDimension('B')->setWidth(30);
        $this->_sheet->getColumnDimension('C')->setAutoSize(true);
        $this->_sheet->getColumnDimension('D')->setAutoSize(true);
        $this->_sheet->getColumnDimension('E')->setAutoSize(true);
        $this->_sheet->getColumnDimension('F')->setAutoSize(true);

    }

    private function _buildOrder(OrderItem $item, &$count)
    {

        $this->_sheet
            ->setCellValue('A' . $count, $item->product->sku)
            ->setCellValue('B' . $count, $item->product->name)
            ->setCellValue('C' . $count, $item->quantity)
            ->setCellValue('D' . $count, $item->price > 0
	            ? Site::convert($item->price, $item->currency_id, $item->currency_id, 1, false, false)
	            : trans('site::price.help.price'))
            ->setCellValue('E' . $count, $item->price > 0
	            ? Site::convert($item->price, $item->currency_id, $item->currency_id, $item->quantity, false, false)
	            : trans('site::price.help.price'));
        $count++;
    }
}
