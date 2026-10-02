<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

/**
 * Class TrimStringsTest.
 */
class TrimStringsTest extends TestCase
{
    /** @test */
    public function it_preserves_spaces_in_embroidery_initial_name_z()
    {
        $payload = [
            'semi_custom_light_jacket' => [
                [
                    'basic_form' => [
                        'embroidery' => [
                            'initialName' => ['z' => ' A B '],
                        ],
                    ],
                ],
            ],
            'semi_custom' => [
                [
                    'option_form' => [
                        'embroidery' => [
                            'initialName' => ['z' => '  X Y  '],
                        ],
                    ],
                ],
            ],
            'customer_name' => '  John Doe  ',
        ];

        $request = Request::create('/', 'POST', [], [], [], [], json_encode($payload));
        $request->headers->set('CONTENT_TYPE', 'application/json');

        (new TrimStrings())->handle($request, function ($req) {
            $this->assertSame(
                ' A B ',
                $req->input('semi_custom_light_jacket.0.basic_form.embroidery.initialName.z')
            );

            $this->assertSame(
                '  X Y  ',
                $req->input('semi_custom.0.option_form.embroidery.initialName.z')
            );

            // other fields must still be trimmed
            $this->assertSame('John Doe', $req->input('customer_name'));

            return 'OK';
        });
    }

    /** @test */
    public function it_still_trims_password_exempted_and_regular_fields_correctly()
    {
        $payload = [
            'password' => '  secret  ',
            'name'     => '  Jane  ',
        ];

        $request = Request::create('/', 'POST', [], [], [], [], json_encode($payload));
        $request->headers->set('CONTENT_TYPE', 'application/json');

        (new TrimStrings())->handle($request, function ($req) {
            $this->assertSame('  secret  ', $req->input('password'));
            $this->assertSame('Jane', $req->input('name'));

            return 'OK';
        });
    }
}
