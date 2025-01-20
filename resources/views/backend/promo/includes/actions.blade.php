<x-utils.edit-button :href="route('admin.promo.edit', ['promo' => $promo])" />

@if ($logged_in_user->hasAllAccess())
    <x-utils.delete-button :href="route('admin.promo.destroy', ['promo' => $promo])" />
@endif
