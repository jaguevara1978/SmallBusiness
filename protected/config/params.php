<?php
return CMap::mergeArray(
		unserialize(base64_decode(file_get_contents(dirname(__FILE__).'/params.inc'))),
		array()
)
;
?>