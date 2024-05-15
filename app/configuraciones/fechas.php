<?php

use Jenssegers\Date\Date;

date_default_timezone_set($_ENV['TIMEZONE']);
Date::setLocale('es');
