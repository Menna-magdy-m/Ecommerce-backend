<?php


namespace App\Services;


use App\Models\Lang;

class LangService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['',
        'name',];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['name'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['name'];
    /**
     *
     */
    protected array $with = ['option_name'];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Lang::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }

     public function update2( array $attributes)
    {
         $record = Lang::first(); 
       
        if($attributes["lang"]){
            
            $record->update(['option_value'=>$attributes["lang"]]);

            }
       

        return $this->ok($record, "language updated");
    }

}
