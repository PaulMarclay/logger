<?php
	set_error_handler(array(new Logger_Handler, 'errorHandler'));
    set_exception_handler(array(new Logger_Handler, 'exceptionHandler'));
    register_shutdown_function(array(new Logger_Handler, 'shutdownHandler'));