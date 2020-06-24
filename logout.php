<?php
session_start();
session_abort(true);
session_unset(true);
header('location:index.php');
