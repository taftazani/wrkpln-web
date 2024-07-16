<?php

namespace App\Services\Organizations;

use App\Repositories\Organizations\OrganizationsRepository;
use Exception;

class OrganizationsService{
    public function __construct(private OrganizationsRepository $organizationsRepository){

    }

    public function doGetAllData(){
        try {
            $repo = $this->organizationsRepository->getAllData();
            return $repo;
        } catch (Exception $e){
            return response()->toJson(['message' => $e->getMessage()], $e->getCode());
        }
    }

    public function doBulkUpload($data){
        try {

            $countedSuccess = 0;
            $countedFailed = 0;

            foreach($data as $key => $val){
                if($val == ''){
                    $countedFailed += 1;
                } else {
                    $countedSuccess += 1;
                }
            }

            $repo = $this->organizationsRepository->postBulkUpload($data);
            return $repo;
        } catch (Exception $e){
            return response()->toJson(['message' => $e->getMessage()], $e->getCode());
        }
    }


}
