<tr>

    <td>
        @if ($isEdit)
        <button type="button" class="btn btn-danger btn-sm" wire:click="cancelEdit"><span class="cil-x-circle"></span></button>
        @else
        <button
            wire:click="editTarget"
            type="button"
            class="btn btn-info btn-sm"><span class="cil-pencil"></span></button>
        @endif
    </td>

    @if ($isEdit)
        <td colspan="2">
            <div class="form-group
                @error('targetNew') has-error @enderror">
                <label for="targetNew">Edit {{$target->isSemiCustom() ? 'Semi Custom' : $target->category->name}}</label>
                <input
                    x-mask:dynamic="$money($input, ',', '.', 0)"
                    type="text" class="form-control form-control-sm" id="targetNew" wire:model="targetNew"  autocomplete="off">
                @error('targetNew')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="mt-1">
                <button type="button" class="btn btn-success btn-sm" wire:click="updateTarget"><span class="cil-check-circle"></span></button>
            </div>
        </td>
    @else
        <td>
            {{$target->isSemiCustom() ? 'Semi Custom' : $target->category->name}}
        </td>
        <td>{{price_format($target->target)}}</td>
    @endif
    <td>
        {{$actualqty}}
    </td>
    <td>
        {{price_format($actualvalue)}}
    </td>

    <td>
        {{$this->indexPercent}} %
    </td>

</tr>
