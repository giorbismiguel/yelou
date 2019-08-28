<?php
$theDistanceTravel = 'El cliente desea trasladarse desde :init_address hasta :end_address';
$theDistanceTravel .= ', la distancia a recorrer es :distance';
$theDistanceTravel .= ' en un tiempo aproximado de :time horas a una velocidad de ';
$theDistanceTravel .= ':velocity. La tarifa es :tariff.';

$textAcceptServiceTransportation = <<<EOF
De clic en Aceptar para prestar el servicio de transportación al cliente.
EOF;

return [
    'message_code_activation'                        => 'Yelou le informa que su código de activación para su cuenta es :code',
    'message_code_activation_subject'                => 'Código de activación para Yelou',
    'message_code_activation_action'                 => 'Ir a Yelou para autenticarse e introducir el código de activación',
    'message_code_activation_line2'                  => 'Si no se registro en Yelou, no es necesario realizar ninguna otra acción.',
    'customer_request_transportation'                => 'Un cliente a realizado una solicitud de taxi',
    'the_distance_travel'                            => $theDistanceTravel,
    'want_accept_request'                            => 'Aceptar el servicio',
    'click_ok_provide_transportation_service_client' => $textAcceptServiceTransportation,
];
