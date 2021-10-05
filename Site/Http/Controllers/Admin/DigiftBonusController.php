<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException;
use ServiceBoiler\Prf\Site\Exports\Excel\DigiftBonusExcel;
use ServiceBoiler\Prf\Site\Filters\DigiftBonus\DigiftBonusUnionExpenseFilter;
use ServiceBoiler\Prf\Site\Models\DigiftBonus;
use ServiceBoiler\Prf\Site\Models\DigiftExpense;
use ServiceBoiler\Prf\Site\Repositories\DigiftBonusRepository;

class DigiftBonusController extends Controller
{

	/**
	 * @var DigiftBonusRepository
	 */
	private $digiftBonuses;

	/**
	 * DigiftUserController constructor.
	 *
	 * @param DigiftBonusRepository $digiftBonuses
	 */
	public function __construct(DigiftBonusRepository $digiftBonuses)
	{
		$this->digiftBonuses = $digiftBonuses;
	}

	public function index(Request $request)
	{

		$bonuses = (new DigiftBonus)->total;
		$expenses = (new DigiftExpense)->total;

		$this->digiftBonuses->trackFilter();
		$repository = $this->digiftBonuses->applyFilter(new DigiftBonusUnionExpenseFilter());
		$digiftBonuses = $this->digiftBonuses->all();
		
		if ($request->has('excel')) {
            (new DigiftBonusExcel())->setRepository($this->digiftBonuses)->render();
        }

		return view('site::admin.digift_bonus.index', compact('repository', 'digiftBonuses', 'bonuses', 'expenses'));
	}

	/**
	 * @param DigiftBonus $digiftBonus
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function changeBalance(DigiftBonus $digiftBonus)
	{

		try {
			$digiftBonus->changeBalance();
			Session::flash('success', trans('site::digift_bonus.changeBalanceSuccess'));
		} catch (GuzzleException $e) {
			Session::flash('error', trans('site::digift.error.admin.guzzle', ['message' => $e->getMessage()]));
		} catch (DigiftException $e) {
			Session::flash('error', trans('site::digift.error.admin.digift', ['message' => $e->getMessage()]));
		} catch (\Exception $e) {
			Session::flash('error', trans('site::digift.error.admin.unknown', ['message' => $e->getMessage()]));
		} finally {
			return response()->json(['replace' => [
				'#digift-bonus' => view('site::admin.digift_bonus.user')->with('bonusable', $digiftBonus->fresh()->bonusable)->render(),
			]], Response::HTTP_OK);
		}
	}

	/**
	 * @param DigiftBonus $digiftBonus
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function rollbackBalanceChange(DigiftBonus $digiftBonus)
	{
		try {
			$digiftBonus->rollbackBalanceChange();
			Session::flash('success', trans('site::digift_bonus.rollbackBalanceChangeSuccess'));
		} catch (GuzzleException $e) {
			Session::flash('error', trans('site::digift.error.admin.guzzle', ['message' => $e->getMessage()]));
		} catch (DigiftException $e) {
			Session::flash('error', trans('site::digift.error.admin.digift', ['message' => $e->getMessage()]));
		} catch (\Exception $e) {
			Session::flash('error', trans('site::digift.error.admin.unknown', ['message' => $e->getMessage()]));
		} finally {
			return response()->json(['replace' => [
				'#digift-bonus' => view('site::admin.digift_bonus.user')->with('bonusable', $digiftBonus->fresh()->bonusable)->render(),
			]], Response::HTTP_OK);
		}
	}

}
