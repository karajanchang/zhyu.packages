<?php

namespace Zhyu\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Zhyu\Facades\ZhyuUrl;

class User extends JsonResource
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
        $modButton->setUrl(route('admin.users.edit', ['id' => $this->id], false));

        $delButton = app()->make('button.destroy', [
            'data' => $this,
            'text' => 'delete',
            'title' => $this->name,
        ]);
        $delUrl = route('admin.users.destroy', ['id' => $this->id], false);
        $delButton->pushAttributes([ 'onclick' => "SwalAlter.delete('".$delUrl."', '刪除', '刪除此筆資料： ".$this->name." - ".$this->route."', '確認刪除')"]);



        $privButton = app()->make('button.create', [
            'text' => '權限設定',
            'url' => route('admin.users.priv', [ 'user' => $this->id ]),
        ]);

        return [
            'id' => $this->id,
            'usergroup' => $this->usergroup->name,
            'nickname' => $this->nickname,
            'name' => $this->name,
            'email' => $this->email,
            'is_online' => $this->is_online==1 ? 'V' : '',
            'buttons' => (string) $modButton. '&nbsp;' . (string) $delButton . '&nbsp;'. '&nbsp;'. $privButton,
        ];
    }
}
