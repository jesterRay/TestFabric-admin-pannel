<?php

namespace App\Models;

use App\Models\Standard;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class TestMethod extends Model
{
    

    public function getTestMethod(){
        try {
            $testMethod = DB::select("SELECT * FROM testfabrics_methods ORDER BY methods__Name");
            return $testMethod;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getTestMethodById($id){
        try {
            $result = DB::select("SELECT * FROM testfabrics_methods where methods__ID = ? ",[$id]);

            if (empty($result)) {
                return null;
            }
            $testMethod = $result[0];
            
            return $testMethod;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    // get Test Method and format it into DataTable format 
    public function getTestMethodForDataTable(){
        try {
            $testMethod = $this->getTestMethod();
            return DataTables::of($testMethod)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $edit_link = route('test-method.edit', $row->methods__ID);
                $delete_link = route('test-method.destroy', $row->methods__ID);
                $view_link = '';
                return view('components.action-button', 
                            compact('edit_link', 'delete_link', 'view_link'))
                            ->render();
            })
            ->rawColumns(['action'])
            ->make(true);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    

    // Add Test Method
    public function addTestMethod($data) {
        try {
            // Insert the record into the database
            $query = "
                INSERT INTO testfabrics_methods 
                (methods__Name, methods__Description, methods__Standard, methods__Standard_id, methods__Sequence, methods__Status)
                VALUES (:name, :description, :standard, :standard_id, :sequence, :status)
            ";

            DB::insert($query, [
                'name' => $data['methods__Name'],
                'description' => $data['methods__Description'] ?? '',
                'standard' => $data['methods__Standard'] ?? '',
                'standard_id' => $data['methods__Standard'] ?? '',
                'sequence' => $data['methods__Sequence'] ?? '',
                'status' => $data['methods__Status'] ?? '',
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding Method: " . $e->getMessage());
        }
    }

    // Update Test Method
    public function updateTestMethod($id, $data) {
        try {
            $query = "
                UPDATE testfabrics_methods 
                SET methods__Name = :name,
                    methods__Description = :description,
                    methods__Standard = :standard,
                    methods__Standard_id = :standard_id,
                    methods__Sequence = :sequence,
                    methods__Status = :status
                WHERE methods__ID = :id
            ";

            DB::update($query, [
                'name' => $data['methods__Name'],
                'description' => $data['methods__Description'] ?? '',
                'standard' => $data['methods__Standard'] ?? '',
                'standard_id' => $data['methods__Standard'] ?? '',
                'sequence' => $data['methods__Sequence'] ?? '',
                'status' => $data['methods__Status'] ?? '',
                'id' => $id,
            ]);

            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating Method: " . $e->getMessage());
        }
    }

    // Delete Test Method
    public function deleteTestMethod($id) {
        try {
            // Delete the record from the database
            $query = "DELETE FROM testfabrics_methods WHERE methods__ID = :id";
            $result = DB::delete($query, ['id' => $id]);

            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
            
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting Method: " . $e->getMessage());
        }
    }


    public function getStandards(){
        $results = (new Standard)->getStandard();
        $standards = collect($results)->map(function($row){
            return (object) [
                "id" => $row->standards__ID,
                "name" => $row->standards__Name
            ];
        });
        return $standards;
    }

}
