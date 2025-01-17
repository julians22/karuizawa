<x-utils.edit-button :href="route('admin.store.edit', ['store' => $store])" />

@if ($logged_in_user->hasAllAccess())
    <x-utils.delete-button :href="route('admin.store.destroy', ['store' => $store])" />
@endif
