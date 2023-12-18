<?php

namespace Pteranodon\Http\Requests\Admin\Pteranodon;

use Pteranodon\Http\Requests\Admin\AdminFormRequest;

class ReferralsFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'enabled' => 'required|in:true,false',
            'reward' => 'required|min:0',
        ];
    }
}
