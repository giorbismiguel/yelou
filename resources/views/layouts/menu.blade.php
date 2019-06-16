<li class="nav-item {{ Request::is('admin/transportationStates*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('admin.transportationStates.index') !!}"><i class="nav-icon icon-cursor"></i><span>Estados de Transportación</span></a>
</li>
<li class="nav-item {{ Request::is('admin/paymentMethods*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('admin.paymentMethods.index') !!}"><i class="nav-icon icon-cursor"></i><span>Métodos de Pagos</span></a>
</li>
<li class="nav-item {{ Request::is('admin/requestedServiceStatuses*') ? 'active' : '' }}">
    <a class="nav-link" href="{!! route('admin.requestedServiceStatuses.index') !!}"><i class="nav-icon icon-cursor"></i><span>Estados Servicios Aceptados</span></a>
</li>
