@props(['href' => '#', 'permission' => false, 'text' => __('Edit')])

<x-utils.link :href="$href" class="btn btn-primary btn-sm" icon="fas fa-pencil-alt" :text="$text" permission="{{ $permission }}" />
