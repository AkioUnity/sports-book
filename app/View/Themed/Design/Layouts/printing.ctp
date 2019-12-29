<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <?php echo $this->MyHtml->css('printing', null, array('fullBase' => true));?>
    </head>
    <body <?php if(in_array($this->Language->getLanguage(), array('fas', 'heb'))): ?>dir="rtl"<?php endif; ?>>
        <?php echo $content_for_layout; ?>
    </body>
</html>