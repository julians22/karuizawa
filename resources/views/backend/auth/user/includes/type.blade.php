@if ($user->isAdmin())
    @lang('Administrator')
@elseif ($user->isUser())
    @lang('User')
@elseif ($user->isCashier())
    @lang('Cashier')
@elseif ($user->isCrew())
    @lang('Crew')
@else
    @lang('N/A')
@endif
