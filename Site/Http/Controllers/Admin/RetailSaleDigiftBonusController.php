<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\DigiftBonusRequest;
use ServiceBoiler\Prf\Site\Models\RetailSaleReport;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException;

class RetailSaleDigiftBonusController extends Controller
{

	/**
	 * @param RetailSaleReport $retailSaleReport
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function store(RetailSaleReport $retailSaleReport)
	{
    
		try {
			/** @var User $user */
			$user = $retailSaleReport->user;
			$user->makeDigiftUser();
			$retailSaleReport->digiftBonus()->create([
				'user_id' => $user->digiftUser->id,
				'operationValue' => $retailSaleReport->total,
			]);
			Session::flash('success', trans('site::digift_bonus.rollbackSuccess'));
		} catch (GuzzleException $e) {
			Session::flash('error', trans('site::digift.error.admin.guzzle', ['message' => $e->getMessage()]));
		} catch (DigiftException $e) {
			Session::flash('error', trans('site::digift.error.admin.digift', ['message' => $e->getMessage()]));
		} catch (\Exception $e) {
			Session::flash('error', trans('site::digift.error.admin.unknown', ['message' => $e->getMessage()]));
		} finally {
			return response()->json(['replace' => [
				'#digift-bonus' => view('site::admin.digift_bonus.user')->with('bonusable', $retailSaleReport)->render(),
			]], Response::HTTP_OK);
		}


	}

	/**
	 * @param  DigiftBonusRequest $request
	 * @param RetailSaleReport $retailSaleReport
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store_mounting(DigiftBonusRequest $request, RetailSaleReport $retailSaleReport)
	{
		$type = 'success';
		$message = trans('site::digift_bonus.created');
		try {
			$request->store_mounting($retailSaleReport);
		} catch (\Exception $exception) {
			$type = 'error';
			$message = $exception->getMessage();
		} finally {
			return redirect()->route('admin.mountings.show', $retailSaleReport)->with($type, $message);
		}


	}


}