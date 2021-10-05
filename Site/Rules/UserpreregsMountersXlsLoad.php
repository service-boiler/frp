<?php

namespace ServiceBoiler\Prf\Site\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use ServiceBoiler\Prf\Site\Models\User;
use ServiceBoiler\Prf\Site\Support\UserpreregsMountersXlsLoadFilter;

use Fomvasss\Dadata\Facades\DadataSuggest;

class UserpreregsMountersXlsLoad implements Rule
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
        $filterSubset = new UserpreregsMountersXlsLoadFilter();
        /** @var BaseReader $reader */
        $reader = IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $reader->setReadFilter($filterSubset);
        try {
            $spreadsheet = $reader->load($file->getPathname());

            $rowIterator = $spreadsheet->getActiveSheet()->getRowIterator();


            $data = [];
            $errors = [];
            $preregs = collect([]);

            foreach ($rowIterator as $r => $row) {
                
                $cellIterator = $row->getCellIterator();

                foreach ($cellIterator as $c => $cell) {

                    $value = $cell->getValue();
                    $data[$r][$c] = $cell->getValue();

                    switch ($c) {
                        case 'A':
                            if (is_null($value) || mb_strlen($value, 'UTF-8') == 0) {
                                $errors[$r][$c] = 'ФИО не заданы';
                            } else {
                                $value = (string)trim($value);
                                if ($preregs->contains($value)) {
                                    $errors[$r][$c] = 'Найдены дубликаты ФИО';
                                } elseif (count(explode(' ', $value))<3) {
                                    $errors[$r][$c] = 'ФИО не полные';
                                } else {
                                    $preregs->push($value);
                                    $data[$r][$c] = $value;
                                }
                            }
                            break;
                            
                        case 'B':
                            $value = (string)trim($value);
                            $data[$r][$c] = $value;
                            break;
                            
                        case 'C':
                            if (is_null($value) || mb_strlen($value, 'UTF-8') == 0) {
                                $errors[$r][$c] = 'email не задан';
                            } else {
                                $value = (string)trim($value);
                                if ($preregs->contains($value)) {
                                    $errors[$r][$c] = 'Найдены дубликаты email';
                                } elseif (User::where('email',$value)->exists()) {
                                    $errors[$r][$c] = 'Пользователь уже зарегистрирован ';
                                } else {
                                    $preregs->push($value);
                                    $data[$r][$c] = $value;
                                }
                            }
                            break;    
                        case 'D':
                                $value = (string)trim($value);
                                if ($preregs->contains($value)) {
                                    $errors[$r][$c] = 'Найдены дубликаты телефонов';
                                } else {
                                    $preregs->push($value);
                                    $data[$r][$c] = $value;
                                }
                            
                            break;
                        case 'E':if (is_null($value) || mb_strlen($value, 'UTF-8') == 0) {
                                //$errors[$r][$c] = 'Город не задан';
                                $data[$r][$c] = $value;
                            } else {
                                $value = (string)trim($value);
                                usleep(100000);
                                 $result = DadataSuggest::suggest("address", ["query"=>"$value", "to_bound"=>['value'=>'city'], "from_bound"=>['value'=>'city']]);
                                 
                                 if (empty($result)) {
                                        $errors[$r][$c] = 'Не найден город в РФ';
                                    } else {
                                        
                                        if(!empty($result['data'])) {
                                            $data[$r][$c] = $value .' ' .$result['data']['region_iso_code'];
                                        } else {
                                            $data[$r][$c] = $value .' ' .$result['0']['data']['region_iso_code'];
                                        }
                                    }
                            }
                            break;
                        case 'F':if (is_null($value) || mb_strlen($value, 'UTF-8') == 0) {
                                
                                $data[$r][$c] = $value;
                            } else {
                                $value = (string)trim($value);
                                usleep(100000);
                                 $result = DadataSuggest::suggest("address", ["query"=>"$value", "to_bound"=>['value'=>'region'], "from_bound"=>['value'=>'region']]);
                                  
                                 if (empty($result)) {
                                        $errors[$r][$c] = 'Не найден регион в РФ';
                                    } else {
                                        $data[$r][$c] = $value .' ' .$result['data']['region_iso_code'];
                                    }
                            }
                            break;
                    }
                }
            }
            
            if (!empty($errors)) {
           
                $this->_errors = view('site::admin.user_prereg.load', compact('data', 'errors'))->render();

                return false;
            }

            return true;
        } catch (Exception $e) {
            $this->_errors = trans('site::preregs.error.load.file', ['error' => $e->getMessage()]);
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