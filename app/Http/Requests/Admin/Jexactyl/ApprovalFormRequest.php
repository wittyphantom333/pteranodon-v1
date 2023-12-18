<?php

namespace Pteranodon\Http\Requests\Admin\Pteranodon;

use Pteranodon\Http\Requests\Admin\AdminFormRequest;

class ApprovalFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'enabled' => 'required|in:true,false',
            'webhook' => 'nullable|active_url',
        ];
    }
}
