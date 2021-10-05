<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Models\Currency;
use ServiceBoiler\Prf\Site\Models\Variable;
use ServiceBoiler\Prf\Site\Repositories\CurrencyRepository;

class CurrencyController extends Controller
{
    /**
     * @var CurrencyRepository
     */
    private $currencies;

    /**
     * Create a new controller instance.
     * @param CurrencyRepository $currencies
     */
    public function __construct(CurrencyRepository $currencies)
    {

        $this->currencies = $currencies;
    }

    /**
     * Show the shop index page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = $this->currencies->paginate(config('site.per_page.currency', 10), ['currencies.*']);
        $variable = Variable::whereName('currency_static_euro')->first();
        return view('site::admin.currency.index', compact('currencies','variable'));
    }

    /**
     * Show the shop index page
     *
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        return view('site::admin.currency.show', compact('currency'));
    }

}