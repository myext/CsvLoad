<?php

namespace Zvg\CsvLoad;

use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\UploadedFile;
use Zvg\CsvLoad\ZvgException;
use \Exception;

class Helper
{

    /**
     * Model for work with info from CSV file
     *
     * @var Illuminate\Database\Eloquent\Model
     */

    protected $model;

    /**
     * Name table for insert info from CSV file
     *
     * @var string
     */

    protected $tableName;


    /**
     * Database name
     *
     * @var string
     */

    protected $dbName;


    /**
     * Name of the uploaded csv file
     *
     * @var string
     */

    protected $file;


    /**
     * Current position of the csv file after reading first line (headers)
     *
     * @var integer
     */

    protected $offset;


    /**
     * Headers from csv file
     *
     * @var array
     */

    protected $csvHeader = [];


    /**
     * Fields from db table
     *
     * @var array
     */

    protected $dbFields = [];


    /**
     * Fields from db table that  there are no in csv file
     *
     * @var array
     */

    protected $diffFields = [];


    public function __construct( $modelName, $file )
    {
        $this->file = $file;

        if(!class_exists($modelName)) throw new ZvgException(trans('zvg::messages.not_model'));//проверка на существование модели
        $this->model = new $modelName();

        $this->tableName = $this->model->getTable();
        if (!Schema::hasTable($this->tableName)) throw new ZvgException(trans('zvg::messages.not_table'));//проверка на существование таблицы

        $this->dbName = env('DB_DATABASE');
    }

    protected function getAllFields()
    {
        $query = "SELECT column_name FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                  WHERE TABLE_SCHEMA = :dbName 
                  AND table_name = :tableName";


        $fields = DB::select($query, [
            'dbName'    => $this->dbName,
            'tableName' => $this->tableName
        ]);

        $allFields = [];

        foreach ($fields as $field){
            $allFields[] = $field->column_name;
        }
        return $allFields;
    }

    protected function getAutoIncrementField()
    {
        $query = "SELECT column_name FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                  WHERE TABLE_SCHEMA= :dbName 
                  AND table_name= :tableName 
                  AND EXTRA like '%auto_increment%'";

        return DB::select($query, [
            'dbName'    => $this->dbName,
            'tableName' => $this->tableName
        ])[0]->column_name;
    }

    public function getOptionalFields()
    {
        $optional_fields[] = $this->model->getUpdatedAtColumn();
        $optional_fields[] = $this->model->getCreatedAtColumn();
        $optional_fields[] = $this->getAutoIncrementField();

        return $optional_fields;
    }

    public function getCsvHeader()
    {
        if (($handle = fopen(storage_path('app/public/'.$this->file), "r")) !== FALSE) {

            $buffer = fgets($handle, 1000);

            $header = explode(config('zvg.delimeter'), $buffer);

            $header = array_map(
                function($item)
                {
                    $item = rtrim($item);
                    $item = filter_var($item, ...config('zvg.filter'));
                    return rtrim($item);

                    }, $header);

            $this->offset = ftell($handle);

            fclose ($handle);

            return $header;
        }
    }

    public function checkCsvHeader()
    {
        $this->csvHeader = $this->getCsvHeader();

        $this->dbFields = $this->getAllFields();

        $optionalFields = $this->getOptionalFields();

        $this->diffFields = array_diff($this->dbFields, $this->csvHeader);

        foreach ($this->diffFields as $field){
            if(!in_array($field, $optionalFields)) return false;
        }
        return true;
    }

    public function getFieldsToInsert()
    {
        $fieldsToInsert = [];

        foreach ($this->dbFields as $field){
            if (!in_array($field, $this->diffFields)){

                $fieldsToInsert[$field] = array_search( $field, $this->csvHeader);
            }
        }

        return $fieldsToInsert;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
}