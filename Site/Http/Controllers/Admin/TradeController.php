<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Filters\CountryEnabledFilter;
use ServiceBoiler\Prf\Site\Filters\Trade\TradePerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Trade\TradeUserFilter;
use ServiceBoiler\Prf\Site\Http\Requests\TradeRequest;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Trade;
use ServiceBoiler\Prf\Site\Repositories\CountryRepository;
use ServiceBoiler\Prf\Site\Repositories\TradeRepository;

class TradeController extends Controller
{

    protected $trades;
    protected $countries;

    /**
     * Create a new controller instance.
     *
     * @param TradeRepository $trades
     * @param CountryRepository $countries
     */
    public function __construct(TradeRepository $trades, CountryRepository $countries)
    {
        $this->trades = $trades;
        $this->countries = $countries;
        $this->countries->trackFilter();
        $this->countries->applyFilter(new CountryEnabledFilter());
    }

    /**
     * Show the user profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $this->trades->trackFilter();
        $this->trades->pushTrackFilter(TradeUserFilter::class);
        $this->trades->pushTrackFilter(TradePerPageFilter::class);
        return view('site::admin.trade.index', [
            'repository' => $this->trades,
            'trades'  => $this->trades->paginate($request->input('filter.per_page', config('site.per_page.trade', 10)), ['trades.*'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Trade $trade
     * @return \Illuminate\Http\Response
     */
    public function edit(Trade $trade)
    {
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();

        return view('site::admin.trade.edit', compact('trade', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TradeRequest $request
     * @param  Trade $trade
     * @return \Illuminate\Http\Response
     */
    public function update(TradeRequest $request, Trade $trade)
    {
        $trade->update($request->input('trade'));

        return redirect()->route('admin.trades.index', ['filter[user]='.$trade->getAttribute('user_id')])->with('success', trans('site::trade.updated'));
    }

}