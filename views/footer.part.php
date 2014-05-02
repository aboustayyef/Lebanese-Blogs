<?php 
// this footer requires a $pageFooter array.
?>


            <div class="endloader" style ="opacity:0"><img src ="<?php echo WEBPATH ;?>img/interface/ajax-loader.gif"></div>
        </div> <!-- /view-area -->
    </div> <!-- /pagewrapper -->
</div>

<?php 

global $startTime;
$endTime = microtime(true);
$executionTime = ($endTime - $startTime);
echo '<!--'.str_repeat('-', 100 ).'-->';
if (@isset($_COOKIE['lebaneseblogs_user_visits'])) {
    echo '<!-- This user has visited this website '.$_COOKIE['lebaneseblogs_user_visits'].' times -->';
}
echo '<!-- php execution took '.sprintf('%f', $executionTime).' seconds to run -->';
?>

    <!-- JS Dependencies -->
    <?php 
        if ($pageFooter['jquery'] == 'CDN'){
            ?>
                <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <?php
        } else if ($pageFooter['jquery'] == 'local'){
            ?>
                <script type="text/javascript" src="<?php echo WEBPATH ;?>js/jquery-1.11.0.min.js"></script>
            <?php
        }
    ?>

    <!-- App Global Variables -->
    <script>
        var global_page = "<?php echo $_SESSION['pageWanted'] ; ?>";
        var global_viewtype = "<?php echo $_SESSION['currentView'] ?>";
        var global_searchTerm = "<?php 
         if (!empty($_SESSION['searchTerm'])) {
            echo $_SESSION['searchTerm'];
         };?>";
        <?php 
            if (@$pageFooter['lefColumnInitialState'] == "closed") {
                ?>var global_leftColumnInitialState = "closed";<?php
            }else {
                ?>var global_leftColumnInitialState = "open";<?php
            };
        ?>

        if (global_page === 'search')
            {
                $(window).load(function()
                {
                    lbApp.Search.init(global_searchTerm);
                });
            };

    </script>
    <!-- App JS 
    <script src="<?php echo WEBPATH ;?>js/lebaneseblogs-ck.js?<?php echo time(); ?>"></script>
    -->
    <script src="<?php echo WEBPATH ;?>js/production/lbApp-ck.js?<?php echo time(); ?>"></script>

<?php 

    if ($pageFooter['statcounter'] == 'yes') {
        ?>
        <!-- Start of StatCounter Code for Default Guide -->
            <script type="text/javascript">
            var sc_project=8489889; 
            var sc_invisible=1; 
            var sc_security="6ec3dc93"; 
            var scJsHost = (("https:" == document.location.protocol) ?
            "https://secure." : "http://www.");
            document.write("<sc"+"ript type='text/javascript' src='" +
            scJsHost +
            "statcounter.com/counter/counter.js'></"+"script>");</script>
            <noscript><div class="statcounter"><a title="web counter"
            href="http://statcounter.com/" target="_blank"><img
            class="statcounter"
            src="https://c.statcounter.com/8489889/0/6ec3dc93/1/"
            alt="web counter"></a></div></noscript>
        <!-- End of StatCounter Code for Default Guide -->
        <?php
    }

    if ($pageFooter['analytics'] == 'yes') {
        ?>
        <!-- Start of Google Analytics Code --> 
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                ga('create', 'UA-40418714-1', 'lebaneseblogs.com');
                ga('send', 'pageview');
            </script>
        <?php
    }

?>   
  </body>
</html>