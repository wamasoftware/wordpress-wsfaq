<?php
if (!defined('ABSPATH'))
    exit;
class SimpleFaqsettings{
    function settings(){
        ?>
<div class="settingsbox">
    <div><h2>How To Use All Shortcode.</h2></div>
<div class="allsettings">
    <h3>Display All FAQ in single page.</h3>
    To display all FAQ in single page.Add following shortcode into page. <br>
    <h4>[simplefaq]</h4>
</div>

<div class="category-settings">
    <h3>If you want single category data in single page.</h3>
    To display all FAQ on single page category wise.Add following shortcode in page.</br>
    <h4>[simplefaq category="14"]</h4>
</div>
    
<div class="category-settings">
    <h3>If you want To put limit into data.</h3>
    To display all FAQ on single page category wise And add limit.Add following shortcode in page.</br>
    <h4>[simplefaq category="14" limit='5']</h4>
</div>
</div>


<?php
    }
}

