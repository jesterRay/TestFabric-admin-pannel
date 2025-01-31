<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class Coc extends Model
{
    public function getCoc(){
        $coc = DB::select('SELECT * FROM testfabrics_coc ORDER BY  files__ID DESC');
        return $coc;
    }

    public function getCocForDataTable(){
        $query = DB::table('testfabrics_coc');

        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('files__download_name',function($row){
            $view_link = url('cocfiles/' . $row->files__download_name);
            return "<a target='_blank' href='{$view_link}'>{$row->files__download_name}</a>";
        })
        ->addColumn('action', function($row) {

            $delete_link = route('coc.destroy', $row->files__ID);
            $edit_link = '';
            $view_link = '';
            return view('components.action-button', compact('edit_link', 'delete_link','view_link'))->render();
        })
        ->rawColumns(['action','files__download_name'])
        ->make(true);
        return $coc;
    }

    public function deleteCoc($id){
        $isDeleted = DB::select('DELETE FROM testfabrics_coc WHERE files__ID=?',[$id]);
        return $isDeleted;
    }
    
    public function saveCoc($file_name,$file_download_name){
        $isSaved = DB::insert(
                        "INSERT INTO testfabrics_coc SET files__Name = ?, files__download_name = ?",
                        [$file_name,$file_download_name]
                    );

        return $isSaved;
    }
}
