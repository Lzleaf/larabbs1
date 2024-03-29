<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'introduction' => 'max:80',
            'avatar' => 'mimes:jepg,bmp,png,jpg,gif|dimensions:min_width=200,min_height=200',
            //
        ];
    }

    public function messages(){
        return [
            'name.required' => '用户名不能为空',
            'name.between' => '用户名必须介于3-25个字符之间',
            'name.unique' => '用户名已被占用，请重新填写',
            'email.unique' => '该邮箱已被注册，请更换邮箱',
            'name.regex' => '用户名只支持英文、数字、横杠、下划线',
            'avatar.mimes' => '头像必须是 jepg, bmp, png, jpg, gif 格式的图片',
            'avatar.dimensions' => '图片清晰度不够，宽和高需要 200px 以上',
        ];
    }
}
