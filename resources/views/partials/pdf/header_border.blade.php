<table class="full-width border-collapse border-l-t-r-1" border="1">
    <tr>
        <td class="w-5 h-10" colspan="4">
            <div class="ml-1">
                <img height="120px" width="350px" src="{{ asset('img/ptaero.png') }}">
            </div>
        </td>
        <td class="text-left font-size-sm" colspan="4">
            <div class="ml-1" style="height: 130px;">
                <div class="font-size-md r-text-bold"> {{ company()->name ?: '' }}, Inc.</div>

                <div style="height: 3px;"></div>

                <div class="r-text-uppercase">
                    {!! company()->address->formatted_without_country ?? '' !!}
                </div>

                <div style="height: 3px;"></div>

                @if(company()->address->phone_number ?? false)
                    <div>
                        <span class="r-text-capitalize">phone:</span>
                        {{ company()->address->phone_number }}
                    </div>
                @endif

                @if(company()->address->fax ?? false)
                    <div>
                        <span class="r-text-capitalize">fax:</span>
                        {{ company()->address->fax }}
                    </div>
                @endif

                @if(company()->email)
                    <div>
                        <span class="r-text-capitalize">Email:</span>
                        {{ company()->email }}
                    </div>
                @endif
            </div>
        </td>
    </tr>
</table>
