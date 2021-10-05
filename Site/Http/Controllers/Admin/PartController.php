<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use ServiceBoiler\Prf\Site\Http\Requests\Admin\PartRequest;
use ServiceBoiler\Prf\Site\Models\Part;
use ServiceBoiler\Prf\Site\Repositories\PartRepository;

class PartController
{

    protected $parts;

    /**
     * Create a new controller instance.
     *
     * @param PartRepository $parts
     */
    public function __construct(PartRepository $parts)
    {
        $this->parts = $parts;
    }


    public function edit(Part $part)
    {
        return view('site::admin.part.edit', compact('part'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PartRequest $request
     * @param  Part $part
     * @return \Illuminate\Http\Response
     */
    public function update(PartRequest $request, Part $part)
    {
        $part->update($request->only(['cost', 'count']));
        return redirect()->route('admin.repairs.show', $part->repair)->with('success', trans('site::part.updated'));
    }

}