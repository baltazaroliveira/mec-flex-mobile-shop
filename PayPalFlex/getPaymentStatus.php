<?php
session_start();
?><status><type><?php echo strtoupper ($_SESSION ['reshash'] ["ACK"] ) ?></type></status>