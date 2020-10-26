<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Services\CheckExtensionServices;
use App\Services\FileUploadServices;

use App\User;

class UserController extends Controller
{

    /**
     * プロフィール画面表示
     *
     * @param [type] $id
     * @return void
     */
    public function show($id) {

        $user = User::findorFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * プロフィール編集画面表示
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $user = User::findorFail($id);

        return view('users.edit', compact('user'));
    }

    /**
     * プロフィール更新
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $user = User::findorFail($id);

        if(!is_null($request['img_name']))
        {
            $imageFile = $request['img_name'];

            $list = FileUploadServices::fileUpload($imageFile);

            list($extension, $fileNameToStore, $fileData) = $list;

            $data_url = CheckExtensionServices::checkExtension($fileData, $extension);
            $image = Image::make($data_url);
            $image->resize(400,400)->save(storage_path() . '/app/public/images/' . $fileNameToStore );

            $user->img_name = $fileNameToStore;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->sex = $request->sex;
        $user->self_introduction = $request->self_introduction;

        $user->save();

        return redirect('home');
    }
}
