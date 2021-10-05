<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use ServiceBoiler\Prf\Site\Exceptions\Certificate\CertificateException;
use ServiceBoiler\Prf\Site\Http\Requests\CertificateServiceRequest;
use ServiceBoiler\Prf\Site\Models\Certificate;
use ServiceBoiler\Prf\Site\Pdf\CertificateServicePdf;
use ServiceBoiler\Prf\Site\Imports\GoogleSheet\CertificateServiceExcel;

class CertificateServiceController extends Controller
{

	use AuthorizesRequests;

	/**
	 * @param CertificateServiceRequest $request
	 * @param CertificateExcel $excel
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	
	public function store(CertificateServiceRequest $request, CertificateServiceExcel $excel)
	{    
		try {
			$type = 'success';
			$message = trans('site::certificate.created');
			$excel->parse($request->input('engineer_id'));
		} catch (\Google_Exception $exception) {
			$type = 'error';
			$message = trans('site::certificate.error.google');
		} catch (CertificateException $exception) {
			$type = 'error';
			$message = $exception->getMessage();
		} catch (\Exception $exception) {
			$type = 'error';
			$message = trans('site::certificate.error.unhandled');
		} finally {
			return redirect()->route('engineers.index')->with($type, $message);
		}

	}


}