<?php

namespace ServiceBoiler\Prf\Site\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Support\ParticipantXlsLoadFilter;

class ParticipantXlsLoad implements Rule
{

    private $_errors;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  UploadedFile $file
     * @return bool
     */
    public function passes($attribute, $file)
    {

        $inputFileType = ucfirst($file->getClientOriginalExtension());
        $filterSubset = new ParticipantXlsLoadFilter();
        /** @var BaseReader $reader */
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter($filterSubset);
        try {
            $spreadsheet = $reader->load($file->getPathname());

            $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();


            $data = [];
            $errors = [];
            $serial = collect([]);

            foreach ($rowIterator as $r => $row) {

                $cellIterator = $row->getCellIterator();

                foreach ($cellIterator as $c => $cell) {

                    $value = $cell->getValue();
                    $data[$r][$c] = $cell->getValue();

                    switch ($c) {
                        case 'A':
                            if (is_null($value) || mb_strlen($value, 'UTF-8') == 0) {
                                $errors[$r][$c] = trans('site::event.error.load_is_null');
                            } else {
                                $value = (string)trim($value);
                                if ($serial->contains($value)) {
                                    $errors[$r][$c] = trans('site::serial.error.load.duplicate');
                                } else {
                                    $serial->push($value);
                                    $data[$r][$c] = $value;
                                }
                            }
                            break;
                        case 'B':
                            $value = (string)trim($value);
                            $data[$r][$c] = $value;
                            break;
                        case 'C':
                            $value = (string)trim($value);
                            $data[$r][$c] = $value;
                            break;
                    }
                }
            }
            
            if (!empty($errors)) {
                $this->_errors = view('site::admin.serial.load', compact('data', 'errors'))->render();

                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->_errors = trans('site::serial.error.load.file', ['error' => $e->getMessage()]);
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->_errors;
    }
}