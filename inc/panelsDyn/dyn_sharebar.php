<?php
    Disp::$content = (show('panels/sharebar', array("url" => $_SESSION['current_site'])));
    Disp::renderMin();