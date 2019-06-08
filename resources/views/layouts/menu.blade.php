<li class="nav-item {{ Request::is('admin/transportationStates*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('admin.transportationStates.index') !!}"><i class="nav-icon icon-cursor"></i><span>TransportationStates</span></a>
</li>
<li class="nav-item {{ Request::is('admin/paymentMethods*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('admin.paymentMethods.index') !!}"><i class="nav-icon icon-cursor"></i><span>PaymentMethods</span></a>
</li>
