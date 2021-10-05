<?php

namespace ServiceBoiler\Prf\Site\Models;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use ServiceBoiler\Prf\Site\Events\Digift\UserBalanceMismatchEvent;
use ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException;
use ServiceBoiler\Prf\Site\Services\Digift;

class DigiftUser extends Model
{

	/**
	 * @var bool
	 */
	public $incrementing = false;
	/**
	 * @var string
	 */
	protected $table = 'digift_users';
	/**
	 * @var array
	 */
	protected $fillable = [
		'id',
		'accessToken',
		'fullUrlToRedirect',
		'tokenExpired',
		'checked_at',
	];

	/**
	 * @var array
	 */
	protected $casts = [
		'id' => 'string',
		'user_id' => 'integer',
		'accessToken' => 'string',
		'fullUrlToRedirect' => 'string',
		'tokenExpired' => 'string',
		'checked_at' => 'timestamp',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Получить текущий баланс
	 * @return int
	 */
	public function getBalanceAttribute()
	{
		return $this->digiftBonuses()
				->where('sended', 1)
				->where('blocked', 0)
				->sum('operationValue') -
			$this->digiftExpenses()
				->sum('operationValue');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function digiftBonuses()
	{
		return $this->hasMany(DigiftBonus::class, 'user_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function digiftExpenses()
	{
		return $this->hasMany(DigiftExpense::class, 'user_id');
	}

	/**
	 * @return mixed
	 */
	public function getDigiftExpensesSumAttribute()
	{
		return $this->digiftExpenses()->sum('operationValue');
	}

	/**
	 * @return mixed
	 */
	public function getAccruedDigiftBonusesSumAttribute()
	{
		return $this->accruedDigiftBonuses()->sum('operationValue');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function accruedDigiftBonuses()
	{
		return $this->digiftBonuses()
			->where('blocked', 0);
	}

	/**
	 * @return mixed
	 */
	public function getSendedDigiftBonusesSumAttribute()
	{
		return $this->sendedDigiftBonuses()->sum('operationValue');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function sendedDigiftBonuses()
	{
		return $this->digiftBonuses()
			->where('sended', 1)
			->where('blocked', 0);
	}

	/**
	 * @throws DigiftException
	 * @throws GuzzleException
	 */
	public function profile()
	{

		$this->update(['checked_at' => now()]);

		if ($this->tokenIsExpired()) {
			$this->refreshToken();
		}

		$response = (new Digift())->profile(['accessToken' => $this->getAttribute('accessToken')])->request();

		if ($response->getStatusCode() === Response::HTTP_OK) {

			$body = json_decode($response->getBody(), true);
			if ($body['code'] == 0) {
				if ($body['result']['balance'] != $this->getAttribute('balance')) {
					event(new UserBalanceMismatchEvent($this, [
						'local' => $this->getAttribute('balance'),
						'remote' => $body['result']['balance'],
					]));
				}
			} else {
				throw new DigiftException($body['message']);
			}
		} else {
			throw new DigiftException($response->getReasonPhrase());
		}
	}

	/**
	 * Проверить срок жизни access токена
	 * @return bool
	 */
	public function tokenIsExpired()
	{
		return is_null($this->getAttribute('tokenExpired')) || time() >= $this->getAttribute('tokenExpired');
	}

	/**
	 * Обновить access token
	 * @throws \ServiceBoiler\Prf\Site\Exceptions\Digift\DigiftException
	 * @throws \GuzzleHttp\Exception\GuzzleException
	 */
	public function refreshToken()
	{

		$response = (new Digift())->authorizeById(['id' => $this->getKey()])->request();

		if ($response->getStatusCode() === Response::HTTP_OK) {

			$body = json_decode($response->getBody(), true);
			if ($body['code'] == 0) {
				$this->update([
					'accessToken' => $body['result']['accessToken'],
					'tokenExpired' => $body['result']['tokenExpired'],
					'fullUrlToRedirect' => $body['result']['fullUrlToRedirect'],
				]);
				$this->fresh();
			} else {
				throw new DigiftException($body['message']);
			}
		} else {
			throw new DigiftException($response->getReasonPhrase());
		}
	}


	/**
	 * @throws DigiftException
	 * @throws GuzzleException
	 */
	public function rollbackBalanceChange()
	{
		/** @var DigiftBonus $digiftBonus */
		foreach ($this->digiftBonuses->where('blocked', 0) as $digiftBonus) {
			$digiftBonus->rollbackBalanceChange();
		}

			$data = [
                                'id' => $this->getKey(),
                                'balance' => '0',
                        ];

                        $response = (new Digift())->setBalance($data)->request();
                        if ($response->getStatusCode() === Response::HTTP_OK) {

                                $body = json_decode($response->getBody(), true);
                                if ($body['code'] == 0) {
                                        $this->update(['sended' => 1, 'digift_resp' => $body['message']]);
                                } else {
                                        throw new DigiftException($body['message']);
                                }
                        } else {
                                throw new DigiftException($response->getReasonPhrase());
                        }

	}

}
