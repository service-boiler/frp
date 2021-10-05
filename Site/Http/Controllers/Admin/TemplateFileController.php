<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use ServiceBoiler\Prf\Site\Concerns\StoreFiles;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\TemplateFileRequest;
use ServiceBoiler\Prf\Site\Models\TemplateFile;
use ServiceBoiler\Prf\Site\Repositories\TemplateFileRepository;

class TemplateFileController extends Controller
{
    use StoreFiles;
    /**
     * @var TemplateFileRepository
     */
    protected $template_files;

    /**
     * Create a new controller instance.
     *
     * @param TemplateFileRepository $template_files
     */
    public function __construct(TemplateFileRepository $template_files)
    {
        $this->template_files = $template_files;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $template_files = $this->template_files->all();

        return view('site::admin.template_file.index', compact('template_files'));
    }

    /**
     * @param TemplateFileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(TemplateFileRequest $request)
    {
        $file = $this->getFile($request);

        return view('site::admin.template_file.create', compact('file'));
    }

    /**
     * @param TemplateFile $template_file
     * @return \Illuminate\Http\Response
     */
    public function show(TemplateFile $template_file)
    {
        return view('site::admin.template_file.show', compact('template_file'));
    }


    /**
     * @param TemplateFileRequest $request
     * @param TemplateFile $template_file
     * @return \Illuminate\Http\Response
     */
    public function edit(TemplateFileRequest $request, TemplateFile $template_file)
    {
        $file = $this->getFile($request, $template_file);

        return view('site::admin.template_file.edit', compact('template_file', 'file'));
    }

    /**
     * @param  TemplateFileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemplateFileRequest $request)
    {

        $template_file = $this->template_files->create($request->input('template_files'));

        return redirect()->route('admin.template-files.show', $template_file)->with('success', trans('site::admin.template_file.created'));
    }

    /**
     * @param  TemplateFileRequest $request
     * @param  TemplateFile $template_file
     * @return \Illuminate\Http\Response
     */
    public function update(TemplateFileRequest $request, TemplateFile $template_file)
    {
        $template_file->update($request->input('template_files'));

        return redirect()->route('admin.template-files.show', $template_file)->with('success', trans('site::admin.template_file.updated'));
    }

}