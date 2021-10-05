<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Contracts\Exchange;
use ServiceBoiler\Prf\Site\Models\CurrencyArchive;

class CurrencyController extends Controller
{

    public function refresh(Exchange $exchange)
    {
        foreach (config('site.update', []) as $update_id) {

            $date = date('Y-m-d');
            $data = $exchange->get($update_id);

            $currency_archive = CurrencyArchive::query()->updateOrCreate(
                ['currency_id' => $update_id, 'date' => $date],
                ['rates' => $data['rates'], 'multiplicity' => $data['multiplicity']]
            );
            $currency_archive->save();
        }
        if (!Auth::guest() && Auth::user()->admin == 1) {
            return redirect()->route('admin.currency_archives.index')->with('success', trans('site::archive.updated'));
        }

        return null;

    }
}