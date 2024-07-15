<?php
namespace App\Repositories\Organizations;

use App\Models\Organization;
use Exception;

class OrganizationsRepository{

    public function getAllData(){
       try {
        $organization = Organization::paginate(10);
        return $organization;
       } catch (Exception $e){
        return response()->toJson(['message' => $e->getMessage()], $e->getCode());

       }
    }

    public function postBulkUpload($data){
        try {
         $organization = Organization::paginate(10);
         return $organization;
        } catch (Exception $e){
         return response()->toJson(['message' => $e->getMessage()], $e->getCode());

        }
     }
}


