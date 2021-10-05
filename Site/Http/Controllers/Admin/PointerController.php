<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\PointerRequest;
use ServiceBoiler\Prf\Site\Models\Pointer;
use ServiceBoiler\Prf\Site\Repositories\PointerRepository;


class PointerController extends Controller
{

    protected $pointers;

    /**
     * Create a new controller instance.
     *
     * @param PointerRepository $pointers
     */
    public function __construct(PointerRepository $pointers)
    {
        $this->pointers = $pointers;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PointerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PointerRequest $request)
    {
        $pointer = $this->pointers->create($request->except(['_token']));
        $element = $pointer->element;
        $json = [];
        $json['append']['#pointers'] = view('site::admin.scheme.pointers.row', compact('pointer', 'element'))->render();
        return response()->json($json);
    }

    public function destroy(Pointer $pointer)
    {

        if ($pointer->delete()) {
            $json['remove'][] = '#pointer-' . $pointer->id;
        } else {
            $json['error'][] = 'error';
        }

        return response()->json($json);
    }

}