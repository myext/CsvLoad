<?php

namespace Zvg\CsvLoad\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Zvg\CsvLoad\Helper;
use \Exception;
use Zvg\CsvLoad\ZvgException;

class CsvLoadController extends Controller
{
    public function load(Request $request)
    {
        try
        {
            $modelName = $request->input('model');

            $csv_file = $request->file('zvg_csv_file');

            if (!$csv_file) throw new ZvgException(trans('zvg::messages.not_file'));


            if ($csv_file->getClientOriginalExtension() != 'csv') throw new ZvgException(trans('zvg::messages.wrong_extention'));//проверка расширения

            $file = $csv_file->storeAs(config('zvg.file_path'), $csv_file->getClientOriginalName(), 'public');


            $helper = new Helper($modelName, $file);


            if (!$helper->checkCsvHeader()) throw new ZvgException(trans('zvg::messages.wrong_header'));

            $fieldsToIsert = $helper->getFieldsToInsert();

            $qeryFields = '(' . implode(',', array_keys($fieldsToIsert)) . ')';


            session(['zvgQeryFields' => $qeryFields]);
            session(['zvgFieldsToInsert' => $fieldsToIsert]);
            session(['zvgCsvOffset' => $helper->getOffset()]);
            session(['zvgCsvFile' => $helper->getFile()]);
            session(['zvgCsvTableName' => $helper->getTableName()]);

            if (config('zvg.mode') == 'update') {
                DB::table($helper->getTableName())->truncate();
            }

            return redirect()->route('zvg.dbinsert');

        }
        catch (ZvgException $e)
        {
            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        catch (Exception $e)
        {
            logger($e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => trans('zvg::messages.common_message')
            ]);
        }
    }

    public function insert()
    {
        try
        {
            $file = storage_path('app/public/' . session('zvgCsvFile'));
            $offset = (int)session('zvgCsvOffset');
            $fieldsToInsert = session('zvgFieldsToInsert');
            $qeryFields = session('zvgQeryFields');
            $table = session('zvgCsvTableName');
            $i = 0;
            $valSet = '';

            if (($handle = fopen($file, "r")) !== FALSE) {

                while (($buffer = fgets($handle, 1000)) !== false) {

                    $current = ftell($handle);

                    if ($current > $offset) {

                        $i++;

                        $val = '';

                        $data = explode(';', $buffer);

                        foreach ($fieldsToInsert as $value) {

                            $dat = rtrim($data[$value]);

                            $dat = filter_var($dat, ...config('zvg.filter'));

                            $dat = "'" . $dat . "'";

                            $val .= $dat . ',';
                        }

                        $val = trim($val, ',');

                        $val = '(' . $val . ')';

                        $valSet .= $val . ',';

                        if ($i == config('zvg.limit')) {

                            $valSet = trim($valSet, ',');

                            DB::insert("insert into $table $qeryFields values $valSet");

                            fclose($handle);

                            session(['zvgCsvOffset' => $current]);

                            return redirect()->route('zvg.dbinsert');
                        }
                    }
                }

                $valSet = trim($valSet, ',');

                DB::insert("insert into $table $qeryFields values $valSet");

                fclose($handle);

                if(!config('zvg.save')) @unlink($file);

                $count = DB::table($table)
                    ->select(DB::raw('count(*) as count'))
                    ->first()
                    ->count;

                return response()->json([
                    'type' => 'success',
                    'message' => trans('zvg::messages.count_insert', ['count' => $count ])
                ]);

            } else throw new Exception(trans('zvg::messages.wrong_file'));
        }
        catch (Exception $e)
        {
            logger($e->getMessage());
            return response()->json([
                'type' => 'success',
                'message' => trans('zvg::messages.common_message')
            ]);
        }

    }
}

