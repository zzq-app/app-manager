<?php

namespace App\Http\Controllers;

use App\Android;
use Illuminate\Http\Request;

class AndroidController extends Controller
{
    /**
     *  @api {post} /api/app/{app_id}/android 上传Android版本资料
     *  @apiName new android
     *  @apiGroup Android
     *  @apiVersion v1.0.0
     *  @apiParam (Must) {string} identification 唯一标识码
     *  @apiParam (Must) {image} file logo图片
     *  @apiParamExample {json} [example]
     *  {
     *      "file": "xxx.jpg",
     *      "identification": "com.xxx.xxxx.xx",
     *  }
     *  @apiSuccess {json} Android 返回保存的Android信息
     *  @apiSuccessExample Success-Response:
     *    HTTP/1.1 200 OK
     *       {
     *        "id": "1",
     *        "app_id": "1",
     *        "logo_url": "public/imgs/xxxxxx.jpg",
     *        "deleted_at": null,
     *        "updated_at": "2017-08-21 16:00",
     *        "created_at": "2017-08-21 16:00",
     *       }
     */
    public function store(Request $request, $app_id) {
        $this->validate($request, [
            'file' => 'required|file',
            'identification' => 'string|required|unique:android,identification',
        ]);
        $path = $request->file('file')->store('public/imgs');
        $android = new Android;
        $android->app_id = $app_id;
        $android->identification = $request->identification;
        $android->logo_url = $path;
        $android->save();
        $response = [
            'id' => $android->id,
            'app_id' => $app_id,
            'logo_url' => $path,
            'identification' => $request->identification,
            'created_at' => $android->created_at->timestamp,
            'updated_at' => $android->updated_at->timestamp,
            'deleted_at' => null,
        ];
        return $response;
    }
    /**
     *  @api {put} /api/app/{app_id}/android 恢复Android版本
     *  @apiName restore android
     *  @apiGroup Android
     *  @apiVersion v1.0.0
     *  @apiParam (null) {null} null 无参数
     *  @apiParamExample {json} [example]
     *  {
     *
     *  }
     *  @apiSuccess {json} Android 返回保存的Android信息
     *  @apiSuccessExample Success-Response:
     *    HTTP/1.1 200 OK
     *       {
     *        "id": "1",
     *        "app_id": "1",
     *        "logo_url": "public/imgs/xxxxxx.jpg",
     *        "deleted_at": null,
     *        "updated_at": "2017-08-21 16:00",
     *        "created_at": "2017-08-21 16:00",
     *       }
     */
    public function restore(Request $request, $app_id) {
        $android = Android::withTrashed()->where('app_id', '=', $app_id)->first();
        if($android->deleted_at !== null) $android->restore();
        $response = [
            'id' => $android->id,
            'app_id' => $app_id,
            'logo_url' => $android->logo_url,
            'identification' => $android->identification,
            'created_at' => $android->created_at->timestamp,
            'updated_at' => $android->updated_at->timestamp,
            'deleted_at' => null,
        ];
        return $response;
    }
    /**
     *  @api {delete} /api/app/{app_id}/android 删除Android版本
     *  @apiName delete android
     *  @apiGroup Android
     *  @apiVersion v1.0.0
     *  @apiParam (null) {null} null 无参数
     *  @apiParamExample {json} [example]
     *  {
     *
     *  }
     *  @apiSuccess {json} Android 返回保存的Android信息
     *  @apiSuccessExample Success-Response:
     *    HTTP/1.1 200 OK
     *       {
     *
     *       }
     */
    public function delete(Request $request, $app_id) {
        $android = Android::withTrashed()->where('app_id', '=', $app_id)->first();
        if($android->deleted_at === null) $android->delete();
        return [];
    }
}