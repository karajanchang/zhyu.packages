<?php
namespace Zhyu\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class DatatableCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $count = $this->collection->count();
        return [
            'draw' => $request->input('draw'),
            'recordsTotal' =>  $count,
            'recordsFiltered' =>  $count,
            'data' =>  $this->collection,
        ];
    }
}
