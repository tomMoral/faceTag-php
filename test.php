<?php

$cmd = "export PYTHON_PATH='/usr/local/lib/python2.7/dist-packages/';";
$cmd .= "python face_detect.py 9079215551_cc8ff2bb53_b0.jpg ";
$cmd .= "9092983716_891c074dbf_b0.jpg 9070410551_dcb214b238_b0.jpg  9080503858_53ac64bf3d_b0.jpg ";
$cmd .= "9093488453_7b3569e637_b0.jpg 9073790081_f6b1b8ea2a_b0.jpg  9080947754_bf7c132b7c_b0.jpg ";
$cmd .= "9093501849_ed6b9598bb_b0.jpg 9075242160_f5ddbc57f6_b0.jpg  9081392612_a71e2d99e0_b0.jpg";
exec( $cmd." 2>&1", $output);
foreach($output as $l)
    echo $l.'<br/>';
?>
