<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Zhyu\Facades\ZhyuUrl;

class Usergroup extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $url = preg_match('/^http/', $this->url) ? $this->url : 'http://'.$this->url;

        $modButton = app()->make('button.edit', [
            'data' => $this,
            'text' => 'modify',
            'title' => $this->name,
        ]);
        $modButton->setUrl(route('admin.usergroups.edit', ['usergroup' => $this->id], false));

        $delButton = app()->make('button.destroy', [
            'data' => $this,
            'text' => 'delete',
            'title' => $this->name,
        ]);
        $delUrl = route('admin.usergroups.destroy', ['usergroup' => $this->id], false);
        $delButton->pushAttributes([ 'onclick' => "SwalAlter.delete('".$delUrl."', '刪除', '刪除此筆資料： ".$this->name." - ".$this->route."', '確認刪除')"]);

        $nextButton = null;
        if(is_null($this->parent_id)) {
            $nextButton = app()->make('button.show', [
                'data' => $this,
                'text' => 'view',
                'title' => $this->name,
            ]);
            $nextUrl = route('admin.usergroups.index', false) . 'query=' . ZhyuUrl::encode('parent_id', '=', $this->id);
            $nextButton->setUrl($nextUrl, false);
        }

        $privButton = app()->make('button.create', [
            'text' => '權限設定',
            'url' => route('admin.usergroups.priv', [ 'id' => $this->id ]),
        ]);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'nologin' => $this->nologin==1 ? 'V' : '',
            'is_online' => $this->is_online==1 ? 'V' : '',
            'buttons' => (string) $modButton. '&nbsp;' . (string) $delButton . '&nbsp;'. (string) $nextButton . '&nbsp;'. $privButton,
        ];
    }
}
