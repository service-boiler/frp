<?php

namespace ServiceBoiler\Prf\Site\Exports\Word;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use ServiceBoiler\Prf\Site\Support\EsbContractProcessor;

class EsbContractWordProcessor extends EsbContractProcessor
{
    function build()
    {
        //dd(trans('site::contract.word.number'));
        $this->templateProcessor = new TemplateProcessor(Storage::disk($this->esbContract->template->file->storage)->getDriver()->getAdapter()->getPathPrefix().$this->esbContract->template->file->path);
        foreach($this->esbContract->template->esbContractFields as $field){
            $this->templateProcessor->setValue($field->name, $this->esbContract->getFieldValue($field));
        }
        foreach($this->esbContract->template->presetFields()->get() as $field){

            $this->templateProcessor->setValue($field->name, $this->esbContract->getFieldValue($field));
        }


        /*$this->templateProcessor->setValue(trans('site::contract.word.number'), $this->contract->number);
        $this->templateProcessor->setValue(trans('site::contract.word.date'), $this->contract->date->format('d.m.Y'));
        $this->templateProcessor->setValue(trans('site::contract.word.executor'), $this->contract->contragent->name);
        $this->templateProcessor->setValue(trans('site::contract.word.signer'), $this->contract->signer);
        $this->templateProcessor->setValue(trans('site::contract.word.territory'), $this->contract->territory);
        $addresses = $this->contract->contragent->addresses()->where('type_id', 1);
        $address = $addresses->exists() ? $addresses->first()->full : '';
        $this->templateProcessor->setValue(trans('site::contract.word.address'), $address);
        $this->templateProcessor->setValue(trans('site::contract.word.phone'), $this->contract->phone);
        $this->templateProcessor->setValue(trans('site::contract.word.email'), $this->contract->contragent->user->email);
        $this->templateProcessor->setValue(trans('site::contract.word.inn'), $this->contract->contragent->inn);
        $this->templateProcessor->setValue(trans('site::contract.word.kpp'), $this->contract->contragent->kpp);
        $this->templateProcessor->setValue(trans('site::contract.word.ogrn'), $this->contract->contragent->ogrn);
        $this->templateProcessor->setValue(trans('site::contract.word.okved'), '');
        $this->templateProcessor->setValue(trans('site::contract.word.bank'), $this->contract->contragent->bank);
        $this->templateProcessor->setValue(trans('site::contract.word.rs'), $this->contract->contragent->rs);
        $this->templateProcessor->setValue(trans('site::contract.word.ks'), $this->contract->contragent->ks);
        $this->templateProcessor->setValue(trans('site::contract.word.bik'), $this->contract->contragent->bik);*/
    }
}