<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Exceptions\Certificate\CertificateException;
use ServiceBoiler\Prf\Site\Http\Requests\CertificateRequest;
use ServiceBoiler\Prf\Site\Models\Certificate;
use ServiceBoiler\Prf\Site\Pdf\CertificatePdf;
use ServiceBoiler\Prf\Site\Pdf\CertificateMounterPdf;
use ServiceBoiler\Prf\Site\Pdf\CertificateServicePdf;
use ServiceBoiler\Prf\Site\Pdf\CertificateSCPdf;
use ServiceBoiler\Prf\Site\Imports\GoogleSheet\CertificateExcel;

class CertificateController extends Controller
{

	use AuthorizesRequests;

	/**
	 * @param CertificateRequest $request
	 * @param CertificateExcel $excel
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(CertificateRequest $request, CertificateExcel $excel)
	{
		try {
			$type = 'success';
			$message = trans('site::certificate.created');
			$excel->parse($request->input('email'));
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
			return redirect()->route('home')->with($type, $message);
		}

	}

	public function show(Certificate $certificate)
	{
		try {
			$this->authorize('view', $certificate);
		} catch (AuthorizationException $e) {
			return redirect()->route('home')->with('error', trans('site::certificate.error.unauthorized'));
		}
        
        if($certificate->type_id=='1') {
		return (new CertificateServicePdf())->setModel($certificate)->render();
        }
        elseif($certificate->type_id=='2') {
        return (new CertificateMounterPdf())->setModel($certificate)->render();
        }
        else {
            //return (new CertificatePdf())->setModel($certificate)->render();
            return redirect()->route('home')->with('error', 'Сертификат не доступен');
        }

	}

	public function sc($type)
	{
		$user=Auth::user();
        //dd($type);
        if($user->hasRole('asc')) {
        return (new CertificateSCPdf())->setModel($user)->setType($type)->render();
        } else { 
            return redirect()->route('home')->with('error', 'Сертификат не доступен');
        }
       

	}

}