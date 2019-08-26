<?php
$theDistanceTravel = 'El cliente desea trasladarse desde <strong> :init_address</strong> hasta <strong>:end_address';
$theDistanceTravel .= '</strong>, la distancia a recorrer es <strong>:distance</strong>';
$theDistanceTravel .= ' en un tiempo aproximado de <strong>:time</strong> horas a una velocidad de <strong>';
$theDistanceTravel .= ':velocity</strong>. La tarifa es <strong>:tariff</strong>.';

return [
    'message_code_activation'                        => 'Yelou le informa que su código de activación para su cuenta es :code',
    'message_code_activation_subject'                => 'Código de activación para Yelou',
    'message_code_activation_action'                 => 'Ir a Yelou para autenticarse e introducir el código de activación',
    'message_code_activation_line2'                  => 'Si no se registro en Yelou, no es necesario realizar ninguna otra acción.',
    'customer_request_transportation'                => 'Un cliente a realizado una solicitud de taxi',
    'the_distance_travel'                            => $theDistanceTravel,
    'want_accept_request'                            => 'Aceptar el servicio',
    'click_ok_provide_transportation_service_client' => <<<EOF
De clic en Aceptar para prestar el servicio de transportación al cliente.
EOF,
];
