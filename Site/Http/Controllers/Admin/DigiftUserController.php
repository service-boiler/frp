<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException;
use ServiceBoiler\Prf\Site\Filters\DigiftBonus\DigiftUserProfileFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\DigiftUserRequest;
use ServiceBoiler\Prf\Site\Models\DigiftUser;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Repositories\DigiftUserRepository;

class DigiftUserController extends Controller
{

	/**
	 * @var DigiftUserRepository
	 */
	private $users;

	/**
	 * DigiftUserController constructor.
	 *
	 * @param DigiftUserRepository $users
	 */
	public function __construct(DigiftUserRepository $users)
	{
		$this->users = $users;
	}

	/**
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
	 */
	public function checkBalance()
	{

		$this->users->applyFilter(new DigiftUserProfileFilter());

		if ($this->users->count() == 0) {
			return response()->json(trans('site::digift_expense.success'), 200);
		}

		/** @var DigiftUser $user */
		foreach ($this->users->all() as $user) {
			$user->checkBalance();
		}

		return response()->redirectToRoute('api.digift.users.checkBalance');
	}

	/**
	 * @param DigiftUser $digiftUser
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function rollbackBalanceChange(DigiftUser $digiftUser)
	{

		try {
			$digiftUser->rollbackBalanceChange();
			Session::flash('success', trans('site::digift_bonus.rollbackBalanceChangeSuccess'));
		} catch (GuzzleException $e) {
			Session::flash('error', trans('site::digift.error.admin.guzzle', ['message' => $e->getMessage()]));
		} catch (DigiftException $e) {
			Session::flash('error', trans('site::digift.error.admin.digift', ['message' => $e->getMessage()]));
		} catch (\Throwable $e) {
			Session::flash('error', trans('site::digift.error.admin.unknown', ['message' => $e->getMessage()]));
		} finally {
			return response()->json(['replace' => [
				'#user-digift-bonuses' => view('site::admin.user.digift_bonus.index')->with('digiftUser', $digiftUser)->render(),
			]], Response::HTTP_OK);
		}

	}


	/**
	 * @param DigiftUser $digiftUser
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refreshToken(DigiftUser $digiftUser)
	{

		try {
			$digiftUser->refreshToken();
			Session::flash('success', trans('site::digift_user.successRefreshToken'));
		} catch (GuzzleException $exception) {
			Session::flash('error', trans('site::digift.error.admin.guzzle', ['message' => $exception->getMessage()]));
		} catch (DigiftException $exception) {
			Session::flash('error', trans('site::digift.error.admin.digift', ['message' => $exception->getMessage()]));
		} catch (\Throwable $exception) {
			Session::flash('error', trans('site::digift.error.admin.unknown', ['message' => $exception->getMessage()]));
		} finally {
			try {
				$content = view('site::admin.user.digift_bonus.index')->with('digiftUser', $digiftUser)->render();
			} catch (\Throwable $e) {
				return response()->json(['error' => $e->getMessage()], Response::HTTP_OK);
			}

			return response()->json(['replace' => [
				'user-digift-bonuses' => $content,
			]], Response::HTTP_OK);
		}
	}


}