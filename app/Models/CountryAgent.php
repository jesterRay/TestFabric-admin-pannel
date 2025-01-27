<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class CountryAgent extends Model
{

    public function getAgents() {
        try {
            $agents = DB::select("SELECT * FROM testfabrics_country_agents ORDER BY agent__Name");
            return $agents;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    public function getAgentById($id) {
        try {
            $result = DB::select("SELECT * FROM testfabrics_country_agents WHERE agent__ID = ?", [$id]);
    
            if (empty($result)) {
                return null;
            }
    
            return $result[0];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // Get agents and format them for DataTables
    public function getAgentsForDataTable() {
        try {
            $agents = $this->getAgents();
            return DataTables::of($agents)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit_link = route('country-agent.edit', $row->agent__ID);
                    $delete_link = route('country-agent.destroy', $row->agent__ID);
                    return view('components.action-button', compact('edit_link', 'delete_link'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    
    // Add a new country agent
    public function addAgent($data) {
        try {
            $query = "
                INSERT INTO testfabrics_country_agents 
                (agent__Name, agent__Email, agent__Password, agent__Country, agent__Website, 
                 agent__Address, agent__Phone, agent__Fax, agent__Latitude, agent__Longitude, agent__Flag, agent__Status)
                VALUES (:name, :email, :password, :country, :website, 
                        :address, :phone, :fax, :latitude, :longitude, :flag, :status)
            ";
    
            DB::insert($query, [
                'name' => $data['agent__Name'],
                'email' => $data['agent__Email'],
                'password' => $data['agent__Password'],
                'country' => $data['agent__Country'],
                'website' => $data['agent__Website'] ?? " ",
                'address' => $data['agent__Address'] ?? " ",
                'phone' => $data['agent__Phone'] ?? " ",
                'fax' => $data['agent__Fax'] ?? " ",
                'latitude' => $data['agent__Latitude'] ?? " ",
                'longitude' => $data['agent__Longitude'] ?? " ",
                'flag' => $data['agent__Flag'] ?? " ",
                'status' => $data['agent__Status'] ?? "" ,
            ]);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error adding country agent: " . $e->getMessage());
        }
    }
    
    // Update an existing country agent
    public function updateAgent($id, $data) {
        try {
            $query = "
                UPDATE testfabrics_country_agents 
                SET agent__Name = :name,
                    agent__Email = :email,
                    agent__Password = :password,
                    agent__Country = :country,
                    agent__Website = :website,
                    agent__Address = :address,
                    agent__Phone = :phone,
                    agent__Fax = :fax,
                    agent__Latitude = :latitude,
                    agent__Longitude = :longitude,
                    agent__Flag = :flag,
                    agent__Status = :status
                WHERE agent__ID = :id
            ";
    
            DB::update($query, [
                'name' => $data['agent__Name'],
                'email' => $data['agent__Email'],
                'password' => $data['agent__Password'],
                'country' => $data['agent__Country'],
                'website' => $data['agent__Website'] ?? " ",
                'address' => $data['agent__Address'] ?? " ",
                'phone' => $data['agent__Phone'] ?? " ",
                'fax' => $data['agent__Fax'] ?? " ",
                'latitude' => $data['agent__Latitude'] ?? " ",
                'longitude' => $data['agent__Longitude'] ?? " ",
                'flag' => $data['agent__Flag'] ?? " ",
                'status' => $data['agent__Status'],
                'id' => $id,
            ]);
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error updating country agent: " . $e->getMessage());
        }
    }
    
    // Delete a country agent
    public function deleteAgent($id) {
        try {
            $query = "DELETE FROM testfabrics_country_agents WHERE agent__ID = :id";
            $result = DB::delete($query, ['id' => $id]);
    
            if ($result === 0) {
                throw new \Exception("No record found with ID {$id}.");
            }
    
            return true;
        } catch (\Exception $e) {
            throw new \Exception("Error deleting country agent: " . $e->getMessage());
        }
    }
    
}
