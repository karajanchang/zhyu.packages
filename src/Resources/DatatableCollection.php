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
        $total = $this->resource->total();
        return [
            'draw' => $request->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' =>  $this->collection,
        ];
    }
}
