<?php

namespace Pteranodon\Http\Requests\Admin\Pteranodon;

use Pteranodon\Http\Requests\Admin\AdminFormRequest;

class AppearanceFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'app:name' => 'required|string|max:191',
            'app:logo' => 'required|string|max:191',
            'theme:user:background' => 'nullable|url',
            'theme:admin' => 'required|string|in:pteranodon,dark,light,blue,minecraft',
        ];
    }
}
