<?php

namespace Pteranodon\Http\Requests\Admin\Pteranodon\Coupons;

use Pteranodon\Http\Requests\Admin\AdminFormRequest;

class IndexFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'enabled' => 'required|boolean',
        ];
    }
}
