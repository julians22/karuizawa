<?php

namespace App\Http\Controllers\Frontend\User;

use File;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SemiCustomConteroller extends Controller
{
    public function index()
    {
        return view('frontend.user.semi-custom', [
            'dataCustomShirt' => $this->dataCustomShirt(),
            'dataCustomRequest' => $this->getCustomRequest(),
        ]);
    }

    private function getCustomRequest()
    {
        $data =  config('semi-custom');
        return collect($data);
    }

    private function dataCustomShirt()
    {
        $data = [
            'collar' => $this->customShirtCollar(),
            'cuffs' => $this->customShirtCuffs(),
            'frontBody' => $this->customShirtFrontBody(),
            'pocket' => $this->customShirtPocket(),
            'hem' => $this->customShirtHem(),
            'backBody' => $this->customShirtBackBody(),
            'button' => $this->customShirtButton(),
        ];
        return collect($data);
    }

    private function getPublicFiles($path)
    {
        $files = File::allFiles(public_path($path));
        $data = [];

        foreach ($files as $key => $value) {
            $explode = explode('.', $value->getFilename());
            $name = Arr::join(explode('-', $explode[1]), ' ');
            $slug = Arr::join(explode('-', $explode[1]), '-');
            $data[] = [
                'no' => $explode[0],
                'name' => ''.$explode[0].'. ' .$name,
                'slug' => $slug,
                'img' => $path . '/' . $value->getFilename(),
            ];
        }

        $sorted = array_values(Arr::sort($data, function (array $value) {
            return (int)$value['no'];
        }));

        return collect($sorted);
    }

    // Custom Shirt
    private function customShirtCollar()
    {
        return $this->getPublicFiles('/img/custom-shirt/collar');
    }

    private function customShirtCuffs()
    {
        return $this->getPublicFiles('/img/custom-shirt/cuffs');
    }

    private function customShirtFrontBody()
    {
        return $this->getPublicFiles('/img/custom-shirt/front-body');
    }

    private function customShirtPocket()
    {
        return $this->getPublicFiles('/img/custom-shirt/pocket');
    }

    private function customShirtHem()
    {
        return $this->getPublicFiles('/img/custom-shirt/hem');
    }

    private function customShirtBackBody()
    {
        return $this->getPublicFiles('/img/custom-shirt/back-body');
    }

    private function customShirtButton()
    {
        return $this->getPublicFiles('/img/custom-shirt/button');
    }
}