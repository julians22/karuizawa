@extends('backend.layouts.app')

@section('title', __('System Information'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('System Information')
        </x-slot>

        <x-slot name="body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Accurate Auth Info

                            <div class="card-header-actions">
                                    <x-utils.link
                                        icon="c-icon cil-sync"
                                        class="card-header-action"
                                        :href="route('admin.system-information.reload-auth-cache')"
                                        :text="__('Reload Accurate Auth Cache')"
                                    />
                            </div>


                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 30px">Key</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><pre>accurate_auth</pre></td>
                                        <td>
                                            <code>
                                                @if (Cache::has('accurate_auth'))
                                                    @php
                                                        $data = cache()->get('accurate_auth');
                                                        $data = json_encode($data, JSON_PRETTY_PRINT);
                                                        echo $data;
                                                    @endphp
                                                @else
                                                    No data
                                                @endif
                                            </code>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-backend.card>
@endsection
